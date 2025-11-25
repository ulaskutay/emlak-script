@extends('layouts.web')

@section('title', 'Hakkımızda')
@section('subtitle', 'Emlak')

@section('content')
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-4xl font-bold text-gray-900 mb-6">Hakkımızda</h1>
            <div class="prose prose-lg max-w-none">
                <p class="text-gray-600 leading-relaxed">
                    {{ $agency_name ?? 'Bizim şirketimiz' }}, emlak sektöründe uzun yıllardır güvenilir hizmet sunmaktadır. 
                    Müşterilerimize en iyi emlak çözümlerini sunmak için çalışıyoruz.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Geniş ilan portföyümüz ve deneyimli ekibimizle, hayalinizdeki emlağı bulmanıza yardımcı oluyoruz.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection

