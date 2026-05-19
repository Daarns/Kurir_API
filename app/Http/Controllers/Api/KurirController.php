<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TambahKurirRequest;
use App\Http\Requests\UbahKurirRequest;
use App\Models\Kurir;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KurirController extends Controller
{
    /**
     * Menampilkan daftar kurir.
     */
    public function index(Request $request)
    {
        $tervalidasi = $request->validate([
            'halaman' => ['sometimes', 'integer', 'min:1'],
            'per_halaman' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'urut' => ['sometimes', 'string', 'in:nama,terdaftar_pada'],
            'cari' => ['sometimes', 'string', 'max:255'],
            'tingkat' => ['sometimes', 'string', 'regex:/^[1-5](,[1-5])*$/'],
        ]);

        $query = Kurir::query();

        if (! empty($tervalidasi['cari'])) {
            $kataKunci = preg_split('/\s+/', trim($tervalidasi['cari'])) ?: [];

            foreach ($kataKunci as $kata) {
                $query->where('nama', 'like', '%'.$kata.'%');
            }
        }

        if (! empty($tervalidasi['tingkat'])) {
            $tingkat = array_map('intval', explode(',', $tervalidasi['tingkat']));
            $query->whereIn('tingkat', $tingkat);
        }

        $urut = $tervalidasi['urut'] ?? 'nama';
        $kurir = $query
            ->orderBy($urut)
            ->paginate((int) ($tervalidasi['per_halaman'] ?? 15), ['*'], 'halaman');

        return response()->json([
            'data' => $kurir->getCollection()->map(fn (Kurir $kurir): array => $this->formatKurir($kurir))->values(),
            'metadata' => [
                'halaman_saat_ini' => $kurir->currentPage(),
                'per_halaman' => $kurir->perPage(),
                'total' => $kurir->total(),
                'halaman_terakhir' => $kurir->lastPage(),
            ],
        ]);
    }

    /**
     * Menyimpan kurir baru.
     */
    public function store(TambahKurirRequest $request)
    {
        $kurir = Kurir::create($request->validated());

        return response()
            ->json(['data' => $this->formatKurir($kurir)], Response::HTTP_CREATED)
            ->header('Location', route('kurir.show', $kurir));
    }

    /**
     * Menampilkan detail kurir.
     */
    public function show(Kurir $kurir)
    {
        return response()->json(['data' => $this->formatKurir($kurir)]);
    }

    /**
     * Mengubah data kurir.
     */
    public function update(UbahKurirRequest $request, Kurir $kurir)
    {
        $kurir->update($request->validated());

        return response()->json(['data' => $this->formatKurir($kurir->refresh())]);
    }

    /**
     * Menghapus kurir.
     */
    public function destroy(Kurir $kurir)
    {
        $kurir->delete();

        return response()->noContent();
    }

    /**
     * @return array<string, mixed>
     */
    private function formatKurir(Kurir $kurir): array
    {
        return [
            'id' => $kurir->id,
            'nama' => $kurir->nama,
            'surel' => $kurir->surel,
            'telepon' => $kurir->telepon,
            'tingkat' => $kurir->tingkat,
            'status' => $kurir->status,
            'jenis_kendaraan' => $kurir->jenis_kendaraan,
            'plat_kendaraan' => $kurir->plat_kendaraan,
            'terdaftar_pada' => $kurir->terdaftar_pada?->toISOString(),
            'alamat' => $kurir->alamat,
            'dibuat_pada' => $kurir->created_at?->toISOString(),
            'diperbarui_pada' => $kurir->updated_at?->toISOString(),
        ];
    }
}
