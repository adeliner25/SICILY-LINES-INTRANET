<?php
namespace App\Http\Controllers;
use App\Http\Requests\FerryRequest;
use App\Models\Ferry;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class FerryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // récupération des données sous forme d'un tableau
        $ferries = Ferry::all();

        // afficher les données sur le template index.blade.php
       return view('index', compact('ferries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Ferry $ferry) : View
    {
        return view("create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FerryRequest $ferryRequest) : RedirectResponse
    {
        $ferry = new Ferry();
        $ferry->nom = $ferryRequest->input('nom');
        $ferry->longueur = $ferryRequest->input('longueur');
        $ferry->largeur = $ferryRequest->input('largeur');
        $ferry->vitesse = $ferryRequest->input('vitesse');

        if($ferryRequest->hasFile('photo')) {
            $ferryRequest->file("photo")->getPathname();
            $imageName=time().'.'.$ferryRequest->photo->extension();
            $ferryRequest->photo->move(public_path('photos'), $imageName);
            $ferry->photo=$imageName;
        } $ferry->save();
        return redirect()->route('Ferry.index')->with('info', "L'élève a bien été crée");
    }

    /**
     * Display the specified resource.
     */
    public function show(Ferry $ferry) : View
    {

        return view("show", compact('ferry'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) : RedirectResponse
    {
        DB::table('ferries')->where('id', $id)->delete();
        return redirect()->route('ferries')->with('success', 'Bateau supprimé.');
    }
    
    public function creerPDF()
    {
    $ferries = Ferry::orderBy('nom')->get();

    $data = [
        'titre' => 'La liste des ferries',
        'date' => date("d/m/y"),
        'ferries' => $ferries,
    ];

    }

}