import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { 
    Users, FileText, CheckCircle, Clock, AlertTriangle, MapPin, 
    Plus, BarChart3, TrendingUp, Home, User
} from 'lucide-react';
import { Link } from '@inertiajs/react';
import { PageProps } from '@/types';

interface DashboardProps extends PageProps {
    stats: {
        total_households: number;
        miskin: number;
        tidak_miskin: number;
        belum_diverifikasi: number;
        disetujui: number;
        perlu_revisi: number;
    };
    roleData: Record<string, number>;
    recentHouseholds: Array<{
        id: number;
        head_of_household_name: string;
        village: string;
        district: string;
        verification_status: string;
        recommendation_status: string | null;
        creator: { name: string };
        verifier?: { name: string };
    }>;
    geoData: Array<{
        id: number;
        head_of_household_name: string;
        latitude: number;
        longitude: number;
        recommendation_status: string | null;
        verification_status: string;
    }>;
    userRole: string;
    [key: string]: unknown;
}

export default function Dashboard({ 
    stats, 
    roleData, 
    recentHouseholds, 
    userRole 
}: DashboardProps) {
    const getStatusBadge = (status: string) => {
        switch (status) {
            case 'belum_diverifikasi':
                return <Badge variant="secondary" className="bg-gray-100 text-gray-800"><Clock className="h-3 w-3 mr-1" />Belum Diverifikasi</Badge>;
            case 'disetujui':
                return <Badge variant="default" className="bg-green-100 text-green-800"><CheckCircle className="h-3 w-3 mr-1" />Disetujui</Badge>;
            case 'perlu_revisi':
                return <Badge variant="destructive" className="bg-red-100 text-red-800"><AlertTriangle className="h-3 w-3 mr-1" />Perlu Revisi</Badge>;
            default:
                return <Badge variant="secondary">{status}</Badge>;
        }
    };

    const getRecommendationBadge = (status: string) => {
        switch (status) {
            case 'miskin':
                return <Badge variant="destructive" className="bg-red-100 text-red-800">Miskin</Badge>;
            case 'tidak_miskin':
                return <Badge variant="default" className="bg-green-100 text-green-800">Tidak Miskin</Badge>;
            default:
                return <Badge variant="secondary">Belum Dinilai</Badge>;
        }
    };

    const getRoleTitle = (role: string) => {
        switch (role) {
            case 'pengisi_data':
                return 'Pengisi Data';
            case 'verifikator':
                return 'Verifikator';
            case 'kepala_dinas':
                return 'Kepala Dinas';
            default:
                return 'Pengguna';
        }
    };

    return (
        <AppShell>
            <Head title="Dashboard - Sistem Pendataan Fakir Miskin" />
            
            <div className="space-y-8">
                {/* Header */}
                <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900">
                            📊 Dashboard Pendataan
                        </h1>
                        <p className="text-gray-600 mt-1">
                            Selamat datang di sistem pendataan fakir miskin DTSEN 2025 - {getRoleTitle(userRole)}
                        </p>
                    </div>
                    
                    {(userRole === 'pengisi_data' || userRole === 'verifikator') && (
                        <Link href="/households/create">
                            <Button className="bg-green-600 hover:bg-green-700">
                                <Plus className="h-4 w-4 mr-2" />
                                Input Data Baru
                            </Button>
                        </Link>
                    )}
                </div>

                {/* Main Statistics */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card className="border-l-4 border-l-blue-500">
                        <CardHeader className="pb-3">
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-sm font-medium text-gray-600">
                                    Total Rumah Tangga
                                </CardTitle>
                                <Users className="h-4 w-4 text-blue-600" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-gray-900">
                                {stats.total_households.toLocaleString()}
                            </div>
                            <p className="text-xs text-gray-600">Data terdaftar</p>
                        </CardContent>
                    </Card>

                    <Card className="border-l-4 border-l-red-500">
                        <CardHeader className="pb-3">
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-sm font-medium text-gray-600">
                                    Rumah Tangga Miskin
                                </CardTitle>
                                <AlertTriangle className="h-4 w-4 text-red-600" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-red-600">
                                {stats.miskin.toLocaleString()}
                            </div>
                            <p className="text-xs text-gray-600">
                                {stats.total_households > 0 ? 
                                    `${((stats.miskin / stats.total_households) * 100).toFixed(1)}% dari total` : 
                                    '0% dari total'
                                }
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="border-l-4 border-l-green-500">
                        <CardHeader className="pb-3">
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-sm font-medium text-gray-600">
                                    Data Terverifikasi
                                </CardTitle>
                                <CheckCircle className="h-4 w-4 text-green-600" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-green-600">
                                {stats.disetujui.toLocaleString()}
                            </div>
                            <p className="text-xs text-gray-600">
                                {stats.total_households > 0 ? 
                                    `${((stats.disetujui / stats.total_households) * 100).toFixed(1)}% dari total` : 
                                    '0% dari total'
                                }
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="border-l-4 border-l-orange-500">
                        <CardHeader className="pb-3">
                            <div className="flex items-center justify-between">
                                <CardTitle className="text-sm font-medium text-gray-600">
                                    Belum Diverifikasi
                                </CardTitle>
                                <Clock className="h-4 w-4 text-orange-600" />
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="text-2xl font-bold text-orange-600">
                                {stats.belum_diverifikasi.toLocaleString()}
                            </div>
                            <p className="text-xs text-gray-600">Menunggu verifikasi</p>
                        </CardContent>
                    </Card>
                </div>

                {/* Role-specific Cards */}
                {userRole === 'pengisi_data' && (
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card className="bg-blue-50 border-blue-200">
                            <CardHeader>
                                <CardTitle className="text-blue-800 flex items-center">
                                    <FileText className="h-5 w-5 mr-2" />
                                    Data Saya
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-blue-900 mb-2">
                                    {roleData.my_households}
                                </div>
                                <p className="text-blue-700 text-sm">Total data yang saya input</p>
                            </CardContent>
                        </Card>

                        <Card className="bg-orange-50 border-orange-200">
                            <CardHeader>
                                <CardTitle className="text-orange-800 flex items-center">
                                    <Clock className="h-5 w-5 mr-2" />
                                    Menunggu Verifikasi
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-orange-900 mb-2">
                                    {roleData.my_pending}
                                </div>
                                <p className="text-orange-700 text-sm">Data menunggu verifikasi</p>
                            </CardContent>
                        </Card>

                        <Card className="bg-green-50 border-green-200">
                            <CardHeader>
                                <CardTitle className="text-green-800 flex items-center">
                                    <CheckCircle className="h-5 w-5 mr-2" />
                                    Disetujui
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-green-900 mb-2">
                                    {roleData.my_approved}
                                </div>
                                <p className="text-green-700 text-sm">Data yang disetujui</p>
                            </CardContent>
                        </Card>
                    </div>
                )}

                {userRole === 'verifikator' && (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Card className="bg-orange-50 border-orange-200">
                            <CardHeader>
                                <CardTitle className="text-orange-800 flex items-center">
                                    <Clock className="h-5 w-5 mr-2" />
                                    Butuh Verifikasi
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-orange-900 mb-2">
                                    {roleData.pending_verification}
                                </div>
                                <p className="text-orange-700 text-sm">Data menunggu verifikasi</p>
                                <Link href="/households?status=belum_diverifikasi" className="mt-3 inline-block">
                                    <Button size="sm" className="bg-orange-600 hover:bg-orange-700">
                                        Mulai Verifikasi
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>

                        <Card className="bg-blue-50 border-blue-200">
                            <CardHeader>
                                <CardTitle className="text-blue-800 flex items-center">
                                    <CheckCircle className="h-5 w-5 mr-2" />
                                    Verifikasi Saya
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-blue-900 mb-2">
                                    {roleData.my_verifications}
                                </div>
                                <p className="text-blue-700 text-sm">Data yang saya verifikasi</p>
                            </CardContent>
                        </Card>
                    </div>
                )}

                {userRole === 'kepala_dinas' && (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <Card className="bg-purple-50 border-purple-200">
                            <CardHeader>
                                <CardTitle className="text-purple-800 flex items-center">
                                    <User className="h-5 w-5 mr-2" />
                                    Total Pengguna
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-purple-900">
                                    {roleData.total_users}
                                </div>
                            </CardContent>
                        </Card>

                        <Card className="bg-green-50 border-green-200">
                            <CardHeader>
                                <CardTitle className="text-green-800 flex items-center">
                                    <Users className="h-5 w-5 mr-2" />
                                    Pengisi Data
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-green-900">
                                    {roleData.pengisi_data_count}
                                </div>
                            </CardContent>
                        </Card>

                        <Card className="bg-blue-50 border-blue-200">
                            <CardHeader>
                                <CardTitle className="text-blue-800 flex items-center">
                                    <MapPin className="h-5 w-5 mr-2" />
                                    Kecamatan
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-blue-900">
                                    {roleData.districts}
                                </div>
                            </CardContent>
                        </Card>

                        <Card className="bg-indigo-50 border-indigo-200">
                            <CardHeader>
                                <CardTitle className="text-indigo-800 flex items-center">
                                    <Home className="h-5 w-5 mr-2" />
                                    Desa
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold text-indigo-900">
                                    {roleData.villages}
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                )}

                {/* Recent Households */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center">
                                <FileText className="h-5 w-5 mr-2 text-green-600" />
                                Data Rumah Tangga Terbaru
                            </CardTitle>
                            <CardDescription>
                                Data terbaru yang diinput ke sistem
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            {recentHouseholds.length > 0 ? (
                                <div className="space-y-4">
                                    {recentHouseholds.map((household) => (
                                        <div key={household.id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div className="flex-1">
                                                <p className="font-medium text-gray-900">
                                                    {household.head_of_household_name}
                                                </p>
                                                <p className="text-sm text-gray-600">
                                                    {household.village}, {household.district}
                                                </p>
                                                <div className="flex items-center gap-2 mt-2">
                                                    {getStatusBadge(household.verification_status)}
                                                    {household.recommendation_status && 
                                                        getRecommendationBadge(household.recommendation_status)
                                                    }
                                                </div>
                                            </div>
                                            <Link href={`/households/${household.id}`}>
                                                <Button size="sm" variant="outline">
                                                    Lihat
                                                </Button>
                                            </Link>
                                        </div>
                                    ))}
                                    <div className="text-center pt-4">
                                        <Link href="/households">
                                            <Button variant="outline" size="sm">
                                                Lihat Semua Data
                                            </Button>
                                        </Link>
                                    </div>
                                </div>
                            ) : (
                                <div className="text-center py-8 text-gray-500">
                                    <FileText className="h-12 w-12 mx-auto mb-4 text-gray-300" />
                                    <p>Belum ada data rumah tangga</p>
                                    {(userRole === 'pengisi_data' || userRole === 'verifikator') && (
                                        <Link href="/households/create" className="mt-4 inline-block">
                                            <Button size="sm" className="bg-green-600 hover:bg-green-700">
                                                <Plus className="h-4 w-4 mr-2" />
                                                Input Data Pertama
                                            </Button>
                                        </Link>
                                    )}
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    {/* Quick Actions */}
                    <Card>
                        <CardHeader>
                            <CardTitle className="flex items-center">
                                <BarChart3 className="h-5 w-5 mr-2 text-blue-600" />
                                Aksi Cepat
                            </CardTitle>
                            <CardDescription>
                                Fitur yang sering digunakan
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <Link href="/households">
                                    <Button variant="outline" className="w-full justify-start">
                                        <FileText className="h-4 w-4 mr-2" />
                                        Lihat Data
                                    </Button>
                                </Link>
                                
                                {(userRole === 'pengisi_data' || userRole === 'verifikator') && (
                                    <Link href="/households/create">
                                        <Button variant="outline" className="w-full justify-start">
                                            <Plus className="h-4 w-4 mr-2" />
                                            Input Data
                                        </Button>
                                    </Link>
                                )}
                                
                                {userRole === 'verifikator' && (
                                    <Link href="/households?status=belum_diverifikasi">
                                        <Button variant="outline" className="w-full justify-start">
                                            <CheckCircle className="h-4 w-4 mr-2" />
                                            Verifikasi
                                        </Button>
                                    </Link>
                                )}
                                
                                {userRole === 'kepala_dinas' && (
                                    <Link href="/reports">
                                        <Button variant="outline" className="w-full justify-start">
                                            <BarChart3 className="h-4 w-4 mr-2" />
                                            Laporan
                                        </Button>
                                    </Link>
                                )}
                                
                                <Link href="/households?map=1">
                                    <Button variant="outline" className="w-full justify-start">
                                        <MapPin className="h-4 w-4 mr-2" />
                                        Peta Sebaran
                                    </Button>
                                </Link>
                                
                                <Button variant="outline" className="w-full justify-start" disabled>
                                    <TrendingUp className="h-4 w-4 mr-2" />
                                    Statistik
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppShell>
    );
}