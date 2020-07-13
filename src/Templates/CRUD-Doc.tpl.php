# [[model_uc]] - `[[model_plural]]`

## To create or replace missing CRUD

Updated for Laravel 7

```
php artisan make:crud [[model_plural]]  --display-name="[[model_uc_plural]]" --grid-columns="name"   # --force --skip-append
```

You will want to adjust the grid-columns to add more columns  for example to add alias

```
--grid-columns="name:alias"
```

To replace one file, remove it and rerun the above command

To replace all files, uncomment `--force`


## After running Crud Generator


#### Setup Permissions in `app/Lib/InitialPermissons.php`

From the bottom of the file put these at the top in alpha order

```
        Permission::findOrCreate('[[model_singular]] index');
        Permission::findOrCreate('[[model_singular]] view');
        Permission::findOrCreate('[[model_singular]] export-pdf');
        Permission::findOrCreate('[[model_singular]] export-excel');
        Permission::findOrCreate('[[model_singular]] add');
        Permission::findOrCreate('[[model_singular]] edit');
        Permission::findOrCreate('[[model_singular]] delete');
```

From the bottom of the file, add these to admin

```
'[[model_singular]] index',
'[[model_singular]] view',
'[[model_singular]] export-pdf',
'[[model_singular]] export-excel',
'[[model_singular]] add',
'[[model_singular]] edit',
'[[model_singular]] delete',
```

From the bottom of the file, add these to read-only

```
        '[[model_singular]] index',
        '[[model_singular]] view',
```

Then run the following to install the permissions

```
php artisan app:set-initial-permissions
```

### Components

In `resource/js/components`


Add

```
Vue.component('[[view_folder]]-grid', () => import(/* webpackChunkName:"[[view_folder]]-grid" */ './components/[[tablename]]/[[model_uc]]Grid.vue'));
Vue.component('[[view_folder]]-form', () => import(/* webpackChunkName:"[[view_folder]]-form" */ './components/[[tablename]]/[[model_uc]]Form.vue'));
Vue.component('[[view_folder]]-show', () => import(/* webpackChunkName:"[[view_folder]]-show" */ './components/[[tablename]]/[[model_uc]]Show.vue'));

```

### Routes

In `routes/web.php


Add

```
Route::get('/api-[[view_folder]]', '[[controller_name]]Api@index');
Route::get('/api-[[view_folder]]/options', '[[controller_name]]Api@getOptions');
Route::get('/[[view_folder]]/download', '[[controller_name]]Controller@download')->name('[[view_folder]].download');
Route::get('/[[view_folder]]/print', '[[controller_name]]Controller@print')->name('[[view_folder]].print');
Route::resource('/[[view_folder]]', '[[controller_name]]Controller');
```

#### Add to the menu in `resources/views/layouts/crud-nav.blade.php`

##### Menu

```
@can(['[[model_singular]] index'])
<li class="nav-item @php if(isset($nav_path[0]) && $nav_path[0] == '[[view_folder]]') echo 'active' @endphp">
    <a class="nav-link" href="{{ route('[[view_folder]].index') }}">[[display_name_singular]] <span
            class="sr-only">(current)</span></a>
</li>
@endcan
```

##### Sub Menu

```
@can(['[[model_singular]] index'])
<a class="dropdown-item @php if(isset($nav_path[1]) && $nav_path[1] == '[[view_folder]]') echo 'active' @endphp"
   href="/[[view_folder]]">[[display_name_singular]]</a>
@endcan
```



## Code Cleanup


```
app/Exports/[[controller_name]]Export.php
app/Http/Controlers/[[controller_name]]Controler.php
app/Http/Controlers/[[controller_name]]Api.php
app/Http/Requests/[[controller_name]]FormRequest.php
app/Http/Requests/[[controller_name]]IndexRequest.php
app/Lib/Import/Import[[controller_name]].php
app/Observers/[[controller_name]]Observer.php
app/[[model_uc]].php
resources/js/components/[[tablename]]
resources/views/[[tablename]]

node_modules/.bin/prettier --write resources/js/components/[[tablename]]/" . [[modelname]] . 'Grid.vue'
node_modules/.bin/prettier --write resources/js/components/[[tablename]]/" . [[modelname]] . 'Form.vue'
node_modules/.bin/prettier --write resources/js/components/[[tablename]]/" . [[modelname]] . 'Show.vue'
```




## FORM Vue component example.
```
<std-form-group
    label="[[model_uc]]"
    label-for="[[model_singular]]_id"
    :errors="form_errors.[[model_singular]]_id">
    <ui-select-pick-one
        url="/api-[[view_folder]]/options"
        v-model="form_data.[[model_singular]]_id"
        :selected_id="form_data.[[model_singular]]_id"
        name="[[model_singular]]_id"
        :blank_value="0">
    </ui-select-pick-one>
</std-form-group>


import UiSelectPickOne from "../SS/UiSelectPickOne";

components: { UiSelectPickOne },
```

## GRID Vue Component example

```
<search-form-group
    class="mb-0"
    label="[[model_uc]]"
    label-for="[[model_singular]]_id"
    :errors="form_errors.[[model_singular]]_id">
    <ui-select-pick-one
        url="/api-[[view_folder]]/options"
        v-model="form_data.[[model_singular]]_id"
        :selected_id="form_data.[[model_singular]]_id"
        name="[[model_singular]]_id"
        blank_text="-- Select One --"
        blank_value="0"
        additional_classes="mb-2 grid-filter">
    </ui-select-pick-one>
</search-form-group>
```
## Blade component example.

### In Controller

```
$[[model_singular]]_options = \App\[[model_uc]]::getOptions();
```


### In View

```
@component('../components/select-pick-one', [
'fld' => '[[model_singular]]_id',
'selected_id' => $RECORD->[[model_singular]]_id,
'first_option' => 'Select a [[model_uc_plural]]',
'options' => $[[model_singular]]_options
])
@endcomponent
```

## Old Stuff that can be ignored

#### Components
 
 In `resource/js/components`
 
Remove

```
Vue.component('[[model_singular]]', require('./components/[[model_singular]].vue').default);
```

#### Remove dead code

```
rm app/Queries/GridQueries/[[controller_name]]Query.php
rm resources/js/components/[[controller_name]]Grid.vue
```


#### Remove from routes

```
Route::get('api/owner-all', '\\App\Queries\GridQueries\OwnerQuery@getAllForSelect');
Route::get('api/owner-one', '\\App\Queries\GridQueries\OwnerQuery@selectOne');
```

#### Remove the Grid Method
vi app/Http/Controllers/ApiController.php


```
// Begin Owner Api Data Grid Method

public function ownerData(Request $request)
{

return GridQuery::sendData($request, 'OwnerQuery');
 
}
 
// End Owner Api Data Grid Method
```
