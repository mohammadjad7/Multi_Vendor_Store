<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = request();
        // QUERY => Returns me values ​​in the array
        // $query = Category::query();


        // SELECT a.*, b.name AS parent_name
        // FROM categories AS a
        // LEFT JOIN categories AS b ON b.id = a.parent_id;

        $categories = Category::with('parent')
            // leftJoin("categories as parents", "parents.id", "=", "categories.parent_id")
            //     ->select(
            //         "categories.*",
            //         "parents.name as parent_name",
            //     )
            ->withCount([
                'products' => function ($query) {
                    $query->where('status', '=', 'active');
                }
            ])
            ->filter($request->query())
            ->orderBy("categories.name")
            ->paginate(4);
        return view("dashboard.categories.index", compact("categories"));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $category = new Category();
        $parents = Category::all();
        return view("dashboard.categories.create", compact('parents', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        //

        $request->validated();

        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        // merge
        // لا تستخدم غير مع الشغلات يلي مو موجودة بالريكوست
        // بما انو عندي حقل ب الداتا بيز بأسم انمج و حقل بالفورم اسمو امج ح يصير فيه خربطة
        //  لان الريكوست فيه حقل و الميرج ما بعدل على قيمة موجودة بالريكوست انما الميرج بس بيضيف  اذا القيمة غير موجودة
        // لهيك بعمل متغير بحط فيه الداتا ما عدا الامنج  و تحت بحط الامج

        $data = $request->except('image');
        $data['image'] = $this->uploadImage($request);
        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categories Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
        return view('dashboard.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        try {

            $category = Category::findOrFail($id);
        } catch (Exception $e) {

            return redirect()->route('categories.index')
                ->with('info', 'THis Category not found 😒');
        }

        //SELECT * FROM categories WHERE id <> $id
        // AND  (parent_id IS NULL OR parent_id <> $id)

        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->Where('parent_id', '<>', $id);
            })
            // ->dd();
            ->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        //
        try {

            $category = Category::findOrFail($id);
        } catch (Exception $e) {

            return redirect()->route('categories.index')
                ->with('info', 'THisn Category not found 😒');
        }
        $old_image = $category->image;

        $data = $request->except('image');


        $new_image = $this->uploadImage($request);
        if ($new_image) {
            $data['image'] = $new_image;
        }

        if ($old_image && $new_image) {
            // لازم حددد الديسك
            Storage::disk('public')->delete($old_image);
        }

        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Categories Updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $category = Category::findOrFail($id);

        $category->delete();

        return redirect()->route('categories.index')
            ->with('delete', 'Categories Deleted!');
    }

    protected function uploadImage(Request $request)
    {
        if (!$request->hasFile('image')) {
            return;
        }
        $file = $request->file('image'); //UploadedFile Object

        // $fileName = $file->getClientOriginalName();

        $path = $file->store('uploads', [
            'disk' => 'public'
        ]);

        return $path;
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(5);
        return view('dashboard.categories.trash', compact('categories'));
    }
    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('categories.trash')
            ->with('success', 'Category restored!');
    }
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);


        $category->forceDelete();

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }
        return redirect()->route('categories.trash')
            ->with('success', 'Category Deleted forever!');
    }
}
