<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb(); // set columns from db columns.

        CRUD::column('featured_image')->type('image')->prefix('storage/')->height('50px')->width('50px');
        CRUD::column('title');
        CRUD::column('content');
        CRUD::column('created_at')->type('datetime');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);
        // CRUD::setFromDb(); // set fields from db columns.

        CRUD::field([   // Text
            'name'  => 'title',
            'label' => "Title",
            'type'  => 'text',

            'attributes' => [
                'placeholder' => 'Post title',
            ],
        ]);

        CRUD::field([
            'name'  => 'content',
            'label' => "Content",
            'type'  => 'textarea',

            'attributes' => [
                'placeholder' => 'Post content',
            ],
        ]);

        CRUD::field([
            'name'      => 'featured_image',
            'label'     => 'Featured image',
            'type'      => 'upload',
            'withFiles' => [
                'disk' => 'public', // the disk where file will be stored
                'path' => 'uploads/featured_images', // the path inside the disk where file will be stored
            ]
        ]);

        // CRUD::field([   // SelectMultiple = n-n relationship (with pivot table)
        //     'label'     => "Tags",
        //     'type'      => 'select_multiple',
        //     'name'      => 'tag_id', // the method that defines the relationship in your Model

        //     // optional
        //     'entity'    => 'tags', // the method that defines the relationship in your Model
        //     'model'     => "App\Models\Tag", // foreign key model
        //     'attribute' => 'name', // foreign key attribute that is shown to user
        //     'pivot'     => true, // on create&update, do you need to add/delete pivot table entries?

        //     // also optional
        //     'options'   => (function ($query) {
        //         return $query->orderBy('name', 'ASC')->get();
        //     }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
        // ]);

        CRUD::field([
            'label'     => "Tag",
            'type'      => 'select',
            'name'      => 'tag_id',
            'entity'    => 'tag',
            'model'     => "App\Models\Tag",
            'attribute' => 'name',
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);

        CRUD::field([
            'label'     => "Category",
            'type'      => 'select',
            'name'      => 'category_id',
            'entity'    => 'category',
            'model'     => "App\Models\Category",
            'attribute' => 'title',
            'options'   => (function ($query) {
                return $query->orderBy('title', 'ASC')->get();
            }),
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
