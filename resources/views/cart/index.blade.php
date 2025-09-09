@extends('app')

@section('title', 'Keranjang Saya')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0"><i class="bi bi-cart-fill"></i> Keranjang Saya</h4>
        </div>
        <div class="card-body bg-light">

            @if ($carts->isEmpty())
                <div class="alert alert-info text-center">Keranjang masih kosong.</div>
            @else
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp
                        @foreach ($carts as $cart)
                            @php 
                                $total = $cart->produk->harga * $cart->quantity; 
                                $grandTotal += $total;
                            @endphp
                            <tr>
                                <td>{{ $cart->produk->nama }}</td>
                                <td>Rp{{ number_format($cart->produk->harga, 0, ',', '.') }}</td>
                                <td>{{ $cart->quantity }}</td>
                                <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="table-secondary">
                            <td colspan="3" class="fw-bold text-end">Grand Total</td>
                            <td class="fw-bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                            <td>
<form action="{{ route('cart.beli', $cart->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-sm btn-success w-100">
        <i class="bi bi-bag-check"></i> Beli Sekarang
    </button>
</form>

                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</div>
@endsection
