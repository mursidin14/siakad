import AlertAction from '@/Components/AlertAction';
import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import AppLayout from '@/Layouts/AppLayout';
import { deleteAction, formatDateIndo } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconArrowsDownUp, IconCalendar, IconPencil, IconPlus, IconRefresh, IconTrash } from '@tabler/icons-react';
import React, { useState } from 'react';


export default function Index(props) {

    const { data: academicYears, meta, links } = props.academicYears;

    console.log(academicYears);

    const [params, setParams] = useState(props.state);

    const onSortable = (field) => {
        setParams({
            ...params,
            field: field,
            direction: params.direction === 'asc' ? 'desc' : 'asc'
        });
    };

    UseFilter({
        route: route('admin.academic-years.index'),
        values: params,
        only: ['academicYears']
    });

    return (
        <div className='flex flex-col w-full pb-32'>
            <div className='flex flex-col items-start justify-between mb-8 gap-y-4 lg:flex-row lg:items-center'>
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconCalendar} />

                <Button
                    variant='orange'
                    size='xl'
                    className='w-full lg:w-auto'
                    asChild
                >
                    <Link href={route('admin.academic-years.create')}>
                        <IconPlus className='size-4' />
                        Tambah
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader className='p-0 mb-4'>
                    {/* Filters IconBuilding */}
                    <div className='flex flex-col w-full gap-4 px-6 py-4 lg:flex-row lg:items-center'>
                        <Input
                            className='w-full sm:w-1/4'
                            placeholder='Search...'
                            value={params?.search}
                            onChange={(e) => setParams((prev) => ({ ...prev, search: e.target.value }))}
                            type='search' />

                        <Select value={params?.load} onValueChange={(e) => setParams({ ...params, load: e })}>
                            <SelectTrigger className='w-full sm:w-24'>
                                <SelectValue placeholder='Load' />
                            </SelectTrigger>
                            <SelectContent>
                                {[10, 25, 50, 100].map((number, index) => (
                                    <SelectItem key={index} value={number}>
                                        {number}
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>

                        <Button variant='red' onClick={() => setParams(props.state)} size='xl'>
                            <IconRefresh className='size-4' />
                            Bersihkan
                        </Button>

                    </div>
                    {/* Show Filters academicYear */}
                    <ShowFilter params={params} />
                </CardHeader>
                <CardContent className='p-0 [&-td]:whitespace-nowrap [&-td]:px-6 [&-th]:px-6'>
                    {academicYears.length === 0 ?
                        (
                            <EmptyState
                                icon={IconCalendar}
                                title='Tidak ada Tahun Akademik'
                                subtitle='Mulailah dengan membuat Tahun Akademik baru' />
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('id')}>
                                                #
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('name')}>
                                                Nama
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('start_date')}>
                                                Tanggal Mulai
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('end_date')}>
                                                Tanggal Berakhir
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('semester')}>
                                                Semester
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('is_active')}>
                                                Aktivasi
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>
                                            <Button variant='ghost' className='inline-flex group' onClick={() => onSortable('created_at')}>
                                                Dibuat pada
                                                <span className='flex-none ml-2 rounded text-muted-foreground'>
                                                    <IconArrowsDownUp className='size-4' />
                                                </span>
                                            </Button>
                                        </TableHead>
                                        <TableHead>Aksi</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {academicYears.map((academicYear, index) => (
                                        <TableRow key={index}>
                                            <TableCell>{index + 1}</TableCell>
                                            <TableCell>{academicYear.name}</TableCell>
                                            <TableCell>{formatDateIndo(academicYear.start_date)}</TableCell>
                                            <TableCell>{formatDateIndo(academicYear.end_date)}</TableCell>
                                            <TableCell>{academicYear.semester}</TableCell>
                                            <TableCell>{academicYear.is_active}</TableCell>
                                            <TableCell>{formatDateIndo(academicYear.created_at)}</TableCell>
                                            <TableCell>
                                                <div className='flex items-center gap-x-1'>
                                                    <Button variant='blue' size='sm'>
                                                        <Link href={route('admin.academic-years.edit', [academicYear])}>
                                                            <IconPencil className='size-4' />
                                                        </Link>
                                                    </Button>
                                                    <AlertAction
                                                        trigger={<Button variant='red' size='sm'>
                                                            <IconTrash className='size' />
                                                        </Button>}

                                                        action={() => deleteAction(route('admin.academic-years.destroy', [academicYear]))} />
                                                </div>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                </CardContent>
                <CardFooter className='flex flex-col items-center justify-between w-full py-3 border-t gap-y-2 lg:flex-row'>
                    <p className='text-sm text-muted-foreground'>
                        Menampilkan <span className='font-medium text-blue-600'>{meta.from ?? 0}</span> dari {meta.total} Tahun Ajaran
                    </p>
                    <div className='overflow-x-auto'>
                        {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                    </div>
                </CardFooter>
            </Card>
        </div>
    );
}

Index.layout = (page) => <AppLayout title={page.props.page_settings.titel} children={page} />;
