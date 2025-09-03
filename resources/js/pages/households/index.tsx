import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { 
    Users, Plus, Search, Filter, MapPin, CheckCircle, Clock, AlertTriangle,
    FileText, User, Home, Calendar, Eye
} from 'lucide-react';
import { PageProps } from '@/types';

interface Household {
    id: number;
    head_of_household_name: string;
    family_card_number: string;
    village: string;
    district: string;
    recommendation_status: string | null;
    verification_status: string;
    created_at: string;
    creator: {
        name: string;
    };
    verifier?: {
        name: string;
    };
}

interface HouseholdsIndexProps extends PageProps {
    households: {
        data: Household[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    filters: {
        search?: string;
        status?: string;
    };
    [key: string]: unknown;
}

export default function HouseholdsIndex({ households, filters }: HouseholdsIndexProps) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [statusFilter, setStatusFilter] = useState(filters.status || '');

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

    const getRecommendationBadge = (status: string | null) => {
        switch (status) {
            case 'miskin':
                return <Badge variant="destructive" className="bg-red-100 text-red-800">Miskin</Badge>;
            case 'tidak_miskin':
                return <Badge variant="default" className="bg-green-100 text-green-800">Tidak Miskin</Badge>;
            default:
                return <Badge variant="secondary" className="bg-gray-100 text-gray-600">Belum Dinilai</Badge>;
        }
    };

    const handleSearch = () => {
        router.get('/households', {
            search: searchQuery,
            status: statusFilter,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const handleStatusFilter = (status: string) => {
        setStatusFilter(status);
        router.get('/households', {
            search: searchQuery,
            status: status,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const resetFilters = () => {
        setSearchQuery('');
        setStatusFilter('');
        router.get('/households', {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    return (
        <AppShell>
            <Head title="Data Rumah Tangga - Sistem Pendataan Fakir Miskin" />

            <div className="space-y-6">
                {/* Header */}
                <div className="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900 flex items-center">
                            <Users className="h-8 w-8 mr-3 text-green-600" />
                            Data Rumah Tangga
                        </h1>
                        <p className="text-gray-600 mt-1">
                            Kelola data rumah tangga berdasarkan formulir DTSEN 2025
                        </p>
                    </div>
                    <Link href="/households/create">
                        <Button className="bg-green-600 hover:bg-green-700">
                            <Plus className="h-4 w-4 mr-2" />
                            Input Data Baru
                        </Button>
                    </Link>
                </div>

                {/* Filters */}
                <Card>
                    <CardContent className="pt-6">
                        <div className="flex flex-col lg:flex-row gap-4 items-end">
                            <div className="flex-1">
                                <label className="text-sm font-medium text-gray-700 mb-2 block">
                                    Cari Data
                                </label>
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-4 w-4" />
                                    <Input
                                        placeholder="Cari nama kepala keluarga, nomor KK, atau desa..."
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        className="pl-10"
                                        onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                                    />
                                </div>
                            </div>
                            <div className="w-full lg:w-48">
                                <label className="text-sm font-medium text-gray-700 mb-2 block">
                                    Status Verifikasi
                                </label>
                                <Select value={statusFilter} onValueChange={handleStatusFilter}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Semua Status" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">Semua Status</SelectItem>
                                        <SelectItem value="belum_diverifikasi">Belum Diverifikasi</SelectItem>
                                        <SelectItem value="disetujui">Disetujui</SelectItem>
                                        <SelectItem value="perlu_revisi">Perlu Revisi</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex gap-2">
                                <Button onClick={handleSearch} className="bg-blue-600 hover:bg-blue-700">
                                    <Search className="h-4 w-4 mr-2" />
                                    Cari
                                </Button>
                                <Button variant="outline" onClick={resetFilters}>
                                    <Filter className="h-4 w-4 mr-2" />
                                    Reset
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Statistics Summary */}
                <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <Card className="border-l-4 border-l-blue-500">
                        <CardContent className="p-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Total Data</p>
                                    <p className="text-2xl font-bold text-blue-600">{households.total}</p>
                                </div>
                                <FileText className="h-8 w-8 text-blue-500" />
                            </div>
                        </CardContent>
                    </Card>
                    
                    <Card className="border-l-4 border-l-orange-500">
                        <CardContent className="p-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Belum Diverifikasi</p>
                                    <p className="text-2xl font-bold text-orange-600">
                                        {households.data.filter(h => h.verification_status === 'belum_diverifikasi').length}
                                    </p>
                                </div>
                                <Clock className="h-8 w-8 text-orange-500" />
                            </div>
                        </CardContent>
                    </Card>
                    
                    <Card className="border-l-4 border-l-green-500">
                        <CardContent className="p-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Disetujui</p>
                                    <p className="text-2xl font-bold text-green-600">
                                        {households.data.filter(h => h.verification_status === 'disetujui').length}
                                    </p>
                                </div>
                                <CheckCircle className="h-8 w-8 text-green-500" />
                            </div>
                        </CardContent>
                    </Card>
                    
                    <Card className="border-l-4 border-l-red-500">
                        <CardContent className="p-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-sm text-gray-600">Perlu Revisi</p>
                                    <p className="text-2xl font-bold text-red-600">
                                        {households.data.filter(h => h.verification_status === 'perlu_revisi').length}
                                    </p>
                                </div>
                                <AlertTriangle className="h-8 w-8 text-red-500" />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Data Table */}
                <Card>
                    <CardHeader>
                        <CardTitle className="flex items-center justify-between">
                            <span>
                                Data Rumah Tangga
                                {households.total > 0 && (
                                    <span className="text-sm text-gray-500 font-normal ml-2">
                                        ({households.data.length} dari {households.total} data)
                                    </span>
                                )}
                            </span>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        {households.data.length > 0 ? (
                            <div className="space-y-4">
                                {households.data.map((household) => (
                                    <div key={household.id} className="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                        <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                            <div className="flex-1">
                                                <div className="flex items-start gap-4">
                                                    <div className="bg-green-100 p-2 rounded-lg">
                                                        <Home className="h-5 w-5 text-green-600" />
                                                    </div>
                                                    <div className="flex-1">
                                                        <h3 className="font-semibold text-lg text-gray-900 mb-1">
                                                            {household.head_of_household_name}
                                                        </h3>
                                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-600">
                                                            <div className="flex items-center">
                                                                <FileText className="h-4 w-4 mr-1 text-gray-400" />
                                                                No. KK: {household.family_card_number}
                                                            </div>
                                                            <div className="flex items-center">
                                                                <MapPin className="h-4 w-4 mr-1 text-gray-400" />
                                                                {household.village}, {household.district}
                                                            </div>
                                                            <div className="flex items-center">
                                                                <User className="h-4 w-4 mr-1 text-gray-400" />
                                                                Input oleh: {household.creator.name}
                                                            </div>
                                                            <div className="flex items-center">
                                                                <Calendar className="h-4 w-4 mr-1 text-gray-400" />
                                                                {new Date(household.created_at).toLocaleDateString('id-ID')}
                                                            </div>
                                                        </div>
                                                        <div className="flex flex-wrap items-center gap-2 mt-3">
                                                            {getStatusBadge(household.verification_status)}
                                                            {getRecommendationBadge(household.recommendation_status)}
                                                            {household.verifier && (
                                                                <Badge variant="outline" className="text-xs">
                                                                    Verifikasi oleh: {household.verifier.name}
                                                                </Badge>
                                                            )}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-2">
                                                <Link href={`/households/${household.id}`}>
                                                    <Button size="sm" variant="outline">
                                                        <Eye className="h-4 w-4 mr-2" />
                                                        Lihat Detail
                                                    </Button>
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                {/* Pagination */}
                                {households.last_page > 1 && (
                                    <div className="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6">
                                        <div className="text-sm text-gray-600">
                                            Menampilkan {households.data.length} dari {households.total} data
                                        </div>
                                        <div className="flex items-center gap-2">
                                            {households.links.map((link, index) => (
                                                <Button
                                                    key={index}
                                                    variant={link.active ? "default" : "outline"}
                                                    size="sm"
                                                    onClick={() => link.url && router.visit(link.url)}
                                                    disabled={!link.url}
                                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                                />
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </div>
                        ) : (
                            <div className="text-center py-12">
                                <Users className="h-16 w-16 mx-auto text-gray-300 mb-4" />
                                <h3 className="text-lg font-medium text-gray-900 mb-2">
                                    Belum Ada Data
                                </h3>
                                <p className="text-gray-600 mb-6">
                                    {filters.search || filters.status 
                                        ? 'Tidak ada data yang sesuai dengan filter yang dipilih.'
                                        : 'Belum ada data rumah tangga yang diinput ke sistem.'
                                    }
                                </p>
                                {(!filters.search && !filters.status) && (
                                    <Link href="/households/create">
                                        <Button className="bg-green-600 hover:bg-green-700">
                                            <Plus className="h-4 w-4 mr-2" />
                                            Input Data Pertama
                                        </Button>
                                    </Link>
                                )}
                                {(filters.search || filters.status) && (
                                    <Button variant="outline" onClick={resetFilters}>
                                        <Filter className="h-4 w-4 mr-2" />
                                        Reset Filter
                                    </Button>
                                )}
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppShell>
    );
}