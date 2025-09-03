import React from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { MapPin, Users, FileText, Shield, BarChart3, Database, CheckCircle } from 'lucide-react';
import { SharedData } from '@/types';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    const features = [
        {
            icon: <FileText className="h-8 w-8 text-green-600" />,
            title: "Input Data DTSEN 2025",
            description: "Formulir lengkap sesuai standar DTSEN 2025 untuk pendataan rumah tangga fakir miskin"
        },
        {
            icon: <MapPin className="h-8 w-8 text-blue-600" />,
            title: "Geotagging & Peta",
            description: "Lokasi akurat dengan koordinat GPS dan visualisasi peta sebaran keluarga"
        },
        {
            icon: <Shield className="h-8 w-8 text-purple-600" />,
            title: "Sistem Verifikasi",
            description: "Alur verifikasi bertingkat dengan status Disetujui, Perlu Revisi, atau Belum Diverifikasi"
        },
        {
            icon: <BarChart3 className="h-8 w-8 text-orange-600" />,
            title: "Laporan & Statistik",
            description: "Dashboard analitik dengan grafik, statistik real-time, dan export ke Excel/PDF"
        }
    ];

    const roles = [
        {
            name: "Pengisi Data",
            description: "Input dan edit data rumah tangga",
            color: "bg-green-100 text-green-800",
            icon: <Database className="h-5 w-5" />
        },
        {
            name: "Verifikator", 
            description: "Verifikasi dan validasi data",
            color: "bg-blue-100 text-blue-800",
            icon: <CheckCircle className="h-5 w-5" />
        },
        {
            name: "Kepala Dinas",
            description: "Laporan dan statistik lengkap",
            color: "bg-purple-100 text-purple-800", 
            icon: <BarChart3 className="h-5 w-5" />
        }
    ];

    const indicators = [
        { label: "Kondisi Rumah", description: "Lantai, dinding, atap" },
        { label: "Sumber Air", description: "PDAM, sumur, mata air" },
        { label: "Fasilitas Sanitasi", description: "Toilet dan pembuangan limbah" },
        { label: "Kepemilikan Aset", description: "Elektronik, kendaraan, ternak" },
        { label: "Sumber Penerangan", description: "PLN, non-PLN, minyak tanah" },
    ];

    if (auth.user) {
        return (
            <>
                <Head title="Dashboard - Sistem Pendataan Fakir Miskin" />
                <div className="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-green-50">
                    <div className="container mx-auto px-4 py-12">
                        <div className="text-center mb-12">
                            <h1 className="text-4xl font-bold text-gray-900 mb-4">
                                📊 Sistem Pendataan Fakir Miskin
                            </h1>
                            <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                                Selamat datang, <span className="font-semibold text-green-600">{auth.user.name}</span>! 
                                Akses dashboard untuk mengelola data rumah tangga DTSEN 2025.
                            </p>
                            <div className="mt-8">
                                <Link href="/dashboard">
                                    <Button size="lg" className="bg-green-600 hover:bg-green-700 text-white px-8 py-4 text-lg">
                                        <BarChart3 className="h-5 w-5 mr-2" />
                                        Buka Dashboard
                                    </Button>
                                </Link>
                            </div>
                        </div>

                        {/* Quick Stats Cards */}
                        <div className="grid md:grid-cols-3 gap-6 mb-12">
                            <Card className="border-green-200">
                                <CardHeader className="pb-3">
                                    <CardTitle className="text-green-700 flex items-center">
                                        <Users className="h-5 w-5 mr-2" />
                                        Data Tersimpan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-sm text-gray-600">Input dan kelola data rumah tangga dengan formulir DTSEN 2025</p>
                                </CardContent>
                            </Card>

                            <Card className="border-blue-200">
                                <CardHeader className="pb-3">
                                    <CardTitle className="text-blue-700 flex items-center">
                                        <CheckCircle className="h-5 w-5 mr-2" />
                                        Verifikasi
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-sm text-gray-600">Sistem verifikasi bertingkat dengan status yang jelas</p>
                                </CardContent>
                            </Card>

                            <Card className="border-purple-200">
                                <CardHeader className="pb-3">
                                    <CardTitle className="text-purple-700 flex items-center">
                                        <BarChart3 className="h-5 w-5 mr-2" />
                                        Laporan
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <p className="text-sm text-gray-600">Dashboard analitik dengan export Excel dan PDF</p>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                </div>
            </>
        );
    }

    return (
        <>
            <Head title="Sistem Pendataan Fakir Miskin - DTSEN 2025" />
            <div className="min-h-screen bg-gradient-to-br from-green-50 via-blue-50 to-green-50">
                {/* Header */}
                <header className="bg-white shadow-sm border-b">
                    <div className="container mx-auto px-4 py-4 flex justify-between items-center">
                        <div className="flex items-center space-x-3">
                            <div className="bg-green-600 text-white p-2 rounded-lg">
                                <Database className="h-6 w-6" />
                            </div>
                            <span className="text-xl font-bold text-gray-900">DTSEN 2025</span>
                        </div>
                        <div className="flex space-x-3">
                            <Link href="/login">
                                <Button variant="outline" className="border-green-600 text-green-600 hover:bg-green-50">
                                    Masuk
                                </Button>
                            </Link>
                            <Link href="/register">
                                <Button className="bg-green-600 hover:bg-green-700 text-white">
                                    Daftar
                                </Button>
                            </Link>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <section className="py-20">
                    <div className="container mx-auto px-4 text-center">
                        <div className="max-w-4xl mx-auto">
                            <h1 className="text-5xl font-bold text-gray-900 mb-6 leading-tight">
                                📊 Sistem Pendataan Fakir Miskin
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                Platform digital untuk pendataan rumah tangga berdasarkan formulir DTSEN 2025. 
                                Kelola data kemiskinan dengan sistem verifikasi bertingkat dan analisis otomatis.
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href="/register">
                                    <Button size="lg" className="bg-green-600 hover:bg-green-700 text-white px-8 py-4 text-lg">
                                        <Users className="h-5 w-5 mr-2" />
                                        Mulai Pendataan
                                    </Button>
                                </Link>
                                <Link href="/login">
                                    <Button size="lg" variant="outline" className="border-green-600 text-green-600 hover:bg-green-50 px-8 py-4 text-lg">
                                        <Shield className="h-5 w-5 mr-2" />
                                        Masuk Sistem
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="py-16 bg-white">
                    <div className="container mx-auto px-4">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">🎯 Fitur Utama Sistem</h2>
                            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                                Solusi lengkap untuk pendataan dan verifikasi data rumah tangga fakir miskin
                            </p>
                        </div>

                        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {features.map((feature, index) => (
                                <Card key={index} className="border-0 shadow-lg hover:shadow-xl transition-shadow duration-300">
                                    <CardHeader className="text-center pb-4">
                                        <div className="mx-auto mb-4 p-3 bg-gray-50 rounded-full w-fit">
                                            {feature.icon}
                                        </div>
                                        <CardTitle className="text-lg text-gray-900">{feature.title}</CardTitle>
                                    </CardHeader>
                                    <CardContent className="text-center">
                                        <CardDescription className="text-gray-600">
                                            {feature.description}
                                        </CardDescription>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Roles Section */}
                <section className="py-16 bg-gray-50">
                    <div className="container mx-auto px-4">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">👥 Tiga Peran Pengguna</h2>
                            <p className="text-lg text-gray-600">
                                Sistem dengan pembagian peran yang jelas untuk efisiensi kerja
                            </p>
                        </div>

                        <div className="grid md:grid-cols-3 gap-8">
                            {roles.map((role, index) => (
                                <Card key={index} className="text-center border-0 shadow-lg">
                                    <CardHeader>
                                        <div className="mx-auto mb-4">
                                            <Badge className={`${role.color} px-4 py-2 flex items-center justify-center w-fit mx-auto`}>
                                                {role.icon}
                                                <span className="ml-2 font-medium">{role.name}</span>
                                            </Badge>
                                        </div>
                                        <CardTitle className="text-gray-900">{role.name}</CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <CardDescription className="text-gray-600">
                                            {role.description}
                                        </CardDescription>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </div>
                </section>

                {/* Indicators Section */}
                <section className="py-16 bg-white">
                    <div className="container mx-auto px-4">
                        <div className="text-center mb-12">
                            <h2 className="text-3xl font-bold text-gray-900 mb-4">🏠 Indikator Kemiskinan</h2>
                            <p className="text-lg text-gray-600 max-w-2xl mx-auto">
                                Sistem otomatis memberikan rekomendasi status MISKIN/TIDAK MISKIN berdasarkan indikator berikut
                            </p>
                        </div>

                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {indicators.map((indicator, index) => (
                                <Card key={index} className="border-l-4 border-l-green-500">
                                    <CardHeader className="pb-3">
                                        <CardTitle className="text-lg text-gray-900 flex items-center">
                                            <CheckCircle className="h-5 w-5 mr-2 text-green-600" />
                                            {indicator.label}
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <CardDescription className="text-gray-600">
                                            {indicator.description}
                                        </CardDescription>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </div>
                </section>

                {/* CTA Section */}
                <section className="py-16 bg-gradient-to-r from-green-600 to-blue-600 text-white">
                    <div className="container mx-auto px-4 text-center">
                        <h2 className="text-3xl font-bold mb-4">🚀 Siap Memulai Pendataan?</h2>
                        <p className="text-xl mb-8 max-w-2xl mx-auto opacity-90">
                            Daftar sekarang dan mulai mengelola data rumah tangga dengan sistem yang profesional dan terintegrasi
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link href="/register">
                                <Button size="lg" className="bg-white text-green-600 hover:bg-gray-100 px-8 py-4 text-lg font-medium">
                                    <Users className="h-5 w-5 mr-2" />
                                    Daftar Sekarang
                                </Button>
                            </Link>
                            <Link href="/login">
                                <Button size="lg" variant="outline" className="border-white text-white hover:bg-white/10 px-8 py-4 text-lg">
                                    <Shield className="h-5 w-5 mr-2" />
                                    Masuk ke Akun
                                </Button>
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Footer */}
                <footer className="bg-gray-900 text-white py-8">
                    <div className="container mx-auto px-4 text-center">
                        <div className="flex items-center justify-center space-x-3 mb-4">
                            <div className="bg-green-600 p-2 rounded-lg">
                                <Database className="h-5 w-5" />
                            </div>
                            <span className="text-xl font-bold">Sistem Pendataan Fakir Miskin</span>
                        </div>
                        <p className="text-gray-400">
                            Platform digital untuk pendataan rumah tangga berdasarkan formulir DTSEN 2025
                        </p>
                        <p className="text-gray-500 text-sm mt-4">
                            © 2024 Sistem Pendataan Fakir Miskin. Semua hak dilindungi.
                        </p>
                    </div>
                </footer>
            </div>
        </>
    );
}