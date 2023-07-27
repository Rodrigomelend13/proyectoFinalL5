<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Lista;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\DB;

class ListaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lista::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $list = new Lista;
        $list->name = $request->input('name');
        $list->active = $request->input('active');
        $list->user_id = $request->input('user_id');
        $list->save();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Lista::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    //    mostrar segun el nombre en especifico
    public function find_by_name(Request $request)
    {
        return Lista::where('name', '=', $request->input('name'))->first();
    }

    // mostrar las lista del usuario
    public function find_by_user_id(Request $request)
    {
        $list = Lista::where('user_id', '=', $request->input('user_id'))->where('active', '=', 1)->first();
        $json_list = new Lista;
        $json_list->id = $list->id;
        $json_list->name = $list->name;

        $all_categories = [];
        foreach ($list->items as $item) {
            $category = (new CategoryController)->show($item->category_id);
            if (!in_array($category, $all_categories)) {
                $all_categories[] = $category;
            }
        }

        $filtered_categories = [];
        $data = [];
        foreach ($all_categories as $category) {
            $filtered_items = $list->items->where("category_id", "=", $category->id);

            $items = [];
            foreach ($filtered_items as $filtered_item) {
                $item = new Item();
                $item->id = $filtered_item->id;
                $item->name = $filtered_item->name;
                $item->note = $filtered_item->note;
                $item->image = $filtered_item->image;
                $item->category_id = $filtered_item->category_id;
                $item->created_at = $filtered_item->created_at;
                $item->updated_at = $filtered_item->updated_at;
                $item->pivot = $filtered_item->pivot;
                $items[] = $item;
            }
            $category->items = $items;
            $filtered_categories[] = $category;
        }

        $json_list['categories'] = $filtered_categories;
        return $json_list;
    }

    // mostrar items segun su categoria

    public function find_items_grouped_by_category(Request $request)
    {
        $list = Lista::where('id', '=', $request->input('list_id'))->first();
        $json_list = new Lista;
        $json_list->id = $list->id;
        $json_list->name = $list->name;

        $all_categories = [];
        foreach ($list->items as $item) {
            $category = (new CategoryController)->show($item->category_id);
            if (!in_array($category, $all_categories)) {
                $all_categories[] = $category;
            }
        }

        $filtered_categories = [];
        $data = [];
        foreach ($all_categories as $category) {
            $filtered_items = $list->items->where("category_id", "=", $category->id);

            $items = [];
            foreach ($filtered_items as $filtered_item) {
                $item = new Item;
                $item->id = $filtered_item->id;
                $item->name = $filtered_item->name;
                $item->note = $filtered_item->note;
                $item->image = $filtered_item->image;
                $item->category_id = $filtered_item->category_id;
                $item->created_at = $filtered_item->created_at;
                $item->updated_at = $filtered_item->updated_at;
                $item->pivot = $filtered_item->pivot;
                $items[] = $item;
            }
            $category->items = $items;
            $filtered_categories[] = $category;
        }

        $json_list['categories'] = $filtered_categories;
        return $json_list;
    }

    // mostrar los elementos de una lista
    public function find_items($id)
    {
        $lista = Lista::findOrFail($id);
        return $lista->items;
    }


    // muestra las listas inactivas por año y meses(no se funciona check!!!)
    public function find_lists_by_month_year(Request $request)
    {
        $months = DB::table("list")
            ->selectRaw("DATE_FORMAT(created_at , '%Y-%m') AS new_date")
            ->where("active", "=", "0")
            ->where("user_id", "=", $request->input('user_id'))
            ->orderBy('created_at', "DESC")
            ->groupBy('created_at')
            ->get();

        $lists_by_month = [];

        foreach ($months as $month) {
            $month_year = $month->new_date;
            $list = DB::table("list")
                ->selectRaw("id,name,canceled,DATE_FORMAT(created_at , '%Y-%m') AS new_date,DATE_FORMAT(created_at , '%d.%m.%Y') AS created_at,DATE_FORMAT(created_at,'%W') AS day,DATE_FORMAT(created_at,'%M') AS month,DATE_FORMAT(created_at , '%Y') AS year")
                ->where("active", "=", "0")
                ->where("user_id", "=", $request->input('user_id'))
                ->where(DB::raw("DATE_FORMAT(created_at , '%Y-%m')"), "=", $month_year)
                ->orderBy('created_at', "DESC")
                ->get();

            $lists_by_month[] = $list;
        }

        return $lists_by_month;
    }

    // muestra listas inactivas por mes (no se si funcione check!!!)
    public function get_number_items_by_month()
    {
        // Consulta para obtener los meses (en formato 'YYYY-MM') de las listas que están inactivas (inactive = 0)
        $months = DB::table("list")
            ->selectRaw("DATE_FORMAT(created_at , '%Y-%m') AS new_date")
            ->where("active", "=", "0")
            ->groupBy(DB::raw("DATE_FORMAT(created_at , '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at , '%Y-%m')"), "DESC")
            ->get();

        $lists_by_month = [];
        foreach ($months as $month) {
            $month_year = $month->new_date;

            $lists = DB::table("list")
                ->selectRaw("id,name,canceled,DATE_FORMAT(created_at , '%Y-%m') AS new_date, DATE_FORMAT(created_at , '%d.%m.%Y') AS created_at, DATE_FORMAT(created_at,'%W') AS day, DATE_FORMAT(created_at,'%M') AS month, DATE_FORMAT(created_at , '%Y') AS year")
                ->where("active", "=", "0")
                ->where(DB::raw("DATE_FORMAT(created_at , '%Y-%m')"), "=", $month_year)
                ->orderBy('created_at', "DESC")
                ->get();

            $count = 0;
            foreach ($lists as $list) {
                $items = DB::table("item_list")
                    ->selectRaw('COUNT(*) AS count')
                    ->where("lista_id", "=", $list->id)
                    ->get();
                $count = $count + $items[0]->count;
            }
            $month_items = [];
            $month_items["name"] = $lists[0]->month;
            $month_items["count"] = $count;
            $lists_by_month[] = $month_items;
        }

        return $lists_by_month;
    }




    //   agregar un item a la lista

    public function add_item_to_list($item_id, $list_id)
    {
        $item = Item::findOrFail($item_id);
        $list = Lista::findOrFail($list_id);
        if (!$item->lists->contains($list_id)) {
            $item->lists()->attach($list);
            return $item;
        }

        return 400;
    }

    //   activar una sola lista e inactivar las demas listas
    public function set_active_list($list_id)
    {
        $lists = Lista::all();
        foreach ($lists as $list) {
            if ($list->id != $list_id and $list->active) {
                $list->active = false;
            } else if ($list->id == $list_id and !$list->active) {
                $list->active = true;
            }
            $list->save();
        }

        return 200;
    }

    // poner una lista activa en inactiva y crear una nueva lista
    public function cancel_complete_list(Request $request)
    {
        $list = Lista::findOrFail($request->input('list_id'));
        $list->canceled = $request->input('canceled');
        $list->active = false;
        $list->save();
        return 200;
    }


    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $list = Lista::findOrFail($request->input('id'));
        $list->name = $request->input('name');
        $list->active = $request->input('active');
        $list->user_id = $request->input('user_id');

        $list->save();

        return $list;
    }

    // Agregar nuevos items y actualice la cantidad para cada item

    public function update_list_items(Request $request)
    {
        $list = Lista::findOrFail($request->input('list_id'));
        $items = json_decode($request->input('items'), true);

        foreach ($items as $item) {
            $this->add_item_to_list($item['id'], $list->id);

            DB::table('items_list')
                ->where([
                    ['item_id', '=', $item['id']],
                    ['lista_id', '=', $request->input('list_id')]
                ])
                ->update(['quantity' => $item['pivot']['quantity']]);
        }
        return $list->items;
    }

    //   actualizar la cantidad de un item en la lista
    public function update_item_quantity(Request $request)
    {

        $item_id = $request->input('item_id');
        $list_id = $request->input('list_id');
        $quantity = $request->input('quantity');

        $item_in_list = DB::table('item_list')
            ->where('item_id', '=', $item_id)
            ->where('lista_id', '=', $list_id)
            ->update(['quantity' => $quantity]);

        return $item_in_list;
    }

    //   eliminar un item de la lista
    public function remove_item_from_list(Request $request)
    {
             DB::table('item_list')
            ->where('item_id', '=', $request->input('item_id'))
            ->where('lista_id', '=', $request->input('list_id'))
            ->delete();

        return 200;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $list = Lista::findOrFail($id);
        $list->delete();
    }
}
