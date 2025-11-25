<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knyga;
use App\Http\Controllers\Controller;
use App\Models\Kategorija;

/**
 * @OA\Tag(
 *     name="Books",
 *     description="API for managing books"
 * )
  * @OA\Schema(
 *     schema="Knyga",
 *     type="object",
 *     required={"pavadinimas","autorius","isbn","kategorija_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="pavadinimas", type="string", example="Nauja knyga"),
 *     @OA\Property(property="autorius", type="string", example="Autorius Vardas"),
 *     @OA\Property(property="aprasymas", type="string", example="Trumpas aprašymas"),
 *     @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
 *     @OA\Property(property="kategorija_id", type="integer", example=1),
 *     @OA\Property(
 *         property="kategorija",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="pavadinimas", type="string", example="Fantastika")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class KnygaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/knygos",
     *     tags={"Books"},
     *     summary="Get all books",
     *     @OA\Response(response=200, description="Successful request")
     * )
     */
    public function index() {
        return response()->json(
            Knyga::with('kategorija')->get(),
            200,
            [],
            JSON_UNESCAPED_UNICODE
        );    
    }

    /**
     * @OA\Post(
     *     path="/api/knygos",
     *     tags={"Books"},
     *     summary="Create a new book",
     *     description="Creates a new book. The `id` is auto-generated and should not be provided.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pavadinimas","autorius","isbn","kategorija_id"},
     *             @OA\Property(property="pavadinimas", type="string", example="Nauja knyga"),
     *             @OA\Property(property="autorius", type="string", example="Autorius Vardas"),
     *             @OA\Property(property="aprasymas", type="string", example="Trumpas aprašymas"),
     *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
     *             @OA\Property(property="kategorija_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="pavadinimas", type="string", example="Nauja knyga"),
     *             @OA\Property(property="autorius", type="string", example="Autorius Vardas"),
     *             @OA\Property(property="aprasymas", type="string", example="Trumpas aprašymas"),
     *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
     *             @OA\Property(property="kategorija_id", type="integer", example=1),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The pavadinimas field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request) {
        $validated = $request->validate([
            'pavadinimas' => 'required|string|max:255',
            'autorius' => 'required|string|max:255',
            'aprasymas' => 'nullable|string',
            'isbn' => 'required|string|unique:knygos,isbn',
            'kategorija_id' => 'required|exists:kategorijas,id'
        ]);

        $knyga = Knyga::create($validated);
        $knyga->load('kategorija'); // Optional: include category
        return response()->json($knyga, 201);
    }


    /**
     * @OA\Get(
     *     path="/api/knygos/{id}",
     *     tags={"Books"},
     *     summary="Get a single book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful request"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function show($id) {
        $knyga = Knyga::with('kategorija')->find($id);
        if (!$knyga) return response()->json(['message' => 'Nerasta'], 404);
        return response()->json($knyga, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/knygos/{id}",
     *     tags={"Books"},
     *     summary="Update a book",
     *     description="Update all fields of a book. The `id` is auto-generated and should not be changed.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"pavadinimas","autorius","isbn","kategorija_id"},
     *             @OA\Property(property="pavadinimas", type="string", example="Atnaujinta knyga"),
     *             @OA\Property(property="autorius", type="string", example="Autorius Vardas"),
     *             @OA\Property(property="aprasymas", type="string", example="Atnaujintas aprašymas"),
     *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
     *             @OA\Property(property="kategorija_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Knyga")
     *     ),
     *     @OA\Response(response=404, description="Book not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, $id) {
        $knyga = Knyga::find($id);
        if (!$knyga) return response()->json(['message' => 'Nerasta'], 404);
        
        $validated = $request->validate([
            'pavadinimas' => 'sometimes|required|string|max:255',
            'autorius' => 'sometimes|required|string|max:255',
            'aprasymas' => 'nullable|string',
            'isbn' => 'sometimes|required|string|unique:knygos,isbn,' . $id,
            'kategorija_id' => 'sometimes|required|exists:kategorijas,id'
        ]);
        
        $knyga->update($validated);
        $knyga->load('kategorija'); // Load relationship for response
        return response()->json($knyga, 200);
    }
    /**
     * @OA\Patch(
    *     path="/api/knygos/{id}",
    *     tags={"Books"},
    *     summary="Partially update a book",
    *     description="Update one or more fields of a book. The `id` is auto-generated.",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="Book ID",
    *         @OA\Schema(type="integer")
    *     ),
    *     @OA\RequestBody(
    *         required=false,
    *         @OA\JsonContent(
    *             @OA\Property(property="pavadinimas", type="string", example="Atnaujinta knyga"),
    *             @OA\Property(property="autorius", type="string", example="Autorius Vardas"),
    *             @OA\Property(property="aprasymas", type="string", example="Atnaujintas aprašymas"),
    *             @OA\Property(property="isbn", type="string", example="978-3-16-148410-0"),
    *             @OA\Property(property="kategorija_id", type="integer", example=1)
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Updated successfully",
    *         @OA\JsonContent(ref="#/components/schemas/Knyga")
    *     ),
    *     @OA\Response(response=404, description="Book not found"),
    *     @OA\Response(response=422, description="Validation error")
    * )
    */
    public function patch(Request $request, $id) {
        $knyga = Knyga::find($id);
        if (!$knyga) return response()->json(['message' => 'Nerasta'], 404);
        
        $validated = $request->validate([
            'pavadinimas' => 'sometimes|string|max:255',
            'autorius' => 'sometimes|string|max:255',
            'aprasymas' => 'nullable|string',
            'isbn' => 'sometimes|string|unique:knygos,isbn,' . $id,
            'kategorija_id' => 'sometimes|exists:kategorijas,id'
        ]);
        
        $knyga->update($validated);
        $knyga->load('kategorija');
        return response()->json($knyga, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/knygos/{id}",
     *     tags={"Books"},
     *     summary="Delete a book",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=404, description="Book not found")
     * )
     */
    public function destroy($id) {
        $knyga = Knyga::find($id);
        if (!$knyga) return response()->json(['message' => 'Nerasta'], 404);
        
        // Check if book has recommendations before deleting
        if ($knyga->rekomendacijos && $knyga->rekomendacijos->count() > 0) {
            return response()->json([
                'message' => 'Negalima ištrinti knygos, kuri turi rekomendacijų'
            ], 422);
        }
        
        $knyga->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/kategorijos/{kategorijaId}/knygos/{knygaId}/rekomendacijos",
     *     tags={"Books"},
     *     summary="Get recommendations for a book in a category",
     *     description="Retrieve all recommendations for a specific book within a specific category.",
     *     @OA\Parameter(
     *         name="kategorijaId",
     *         in="path",
     *         required=true,
     *         description="ID of the category",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="knygaId",
     *         in="path",
     *         required=true,
     *         description="ID of the book",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of recommendations or a message if none exist",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     type="array",
     *                     @OA\Items(ref="#/components/schemas/Rekomendacija")
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     @OA\Property(property="message", type="string", example="Šiai knygai dar nėra rekomendacijų")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Category or book not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Knyga nerasta šioje kategorijoje")
     *         )
     *     )
     * )
     */
    public function rekomendacijosPagalKategorija($kategorijaId, $knygaId)
    {
        // Find the book with the category and eager load recommendations
        $knyga = Knyga::with('rekomendacijos')
            ->where('id', $knygaId)
            ->where('kategorija_id', $kategorijaId)
            ->first();

        if (!$knyga) {
            return response()->json(['message' => 'Knyga nerasta šioje kategorijoje'], 404);
        }

        $rekomendacijos = $knyga->rekomendacijos;

        if ($rekomendacijos->isEmpty()) {
            return response()->json(['message' => 'Šiai knygai dar nėra rekomendacijų'], 200);
        }

        return response()->json($rekomendacijos, 200);
    }

}