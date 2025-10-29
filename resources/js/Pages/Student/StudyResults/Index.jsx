import EmptyState from '@/Components/EmptyState';
import Grades from '@/Components/Grades';
import HeaderTitle from '@/Components/HeaderTitle';
import PaginationTable from '@/Components/PaginationTable';
import ShowFilter from '@/Components/ShowFilter';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table';
import UseFilter from '@/hooks/UseFilter';
import StudentLayout from '@/Layouts/StudentLayout';
import { formatDateIndo, STUDYPLANSTATUSVARIANT } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { IconArrowsDownUp, IconBuilding, IconEye, IconPlus, IconRefresh, IconSchool } from '@tabler/icons-react';
import { useState } from 'react';

export default function Index(props) {
    const { data: studyResults, meta, links } = props.studyResults;

    const [params, setParams] = useState(props.state);


    return (
        <div className="flex w-full flex-col">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconSchool}
                />
            </div>

            <div className="flex flex-col gap-y-8">

                {/* Show Filters */}
                <ShowFilter params={params} />
                {studyResults.length === 0 ? (
                    <EmptyState 
                        icon={IconSchool} 
                        title="Tidak ada KHS" 
                        subtitle="Mulailah dengan membuat KHS baru" 
                        />
                ) : (
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>#</TableHead>
                                <TableHead>Tahun Ajaran</TableHead>
                                <TableHead>Semester</TableHead>
                                <TableHead>GPA</TableHead>
                                <TableHead>Dibuat Pada</TableHead>
                                <TableHead>Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {studyResults.map((studyResult, index) => (
                                <TableRow key={index}>
                                    <TableCell>{index + 1 + (meta.current_page - 1) * meta.per_page}</TableCell>
                                    <TableCell>{studyResult.academicYear.name}</TableCell>
                                    <TableCell>{studyResult.semester}</TableCell>
                                    <TableCell>{studyResult.gpa}</TableCell>
                                    <TableCell>{formatDateIndo(studyResult.created_at)}</TableCell>
                                    <TableCell>
                                        <div className="flex items-center gap-x-1">
                                            <Grades 
                                                studyResult={studyResult}
                                                grades={studyResult.grades}
                                            />
                                        </div>
                                    </TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                )}
                <div className="flex w-full flex-col items-center justify-between gap-y-2 lg:flex-row">
                    <p className="text-sm text-muted-foreground">
                        Menampilkan <span className="font-medium text-blue-600">{meta.from ?? 0}</span> dari{' '}
                        {meta.total} Kartu Hasil Studi
                    </p>
                    <div className="overflow-x-auto">
                        {meta.has_pages && <PaginationTable meta={meta} links={links} />}
                    </div>
                </div>
            </div>
        </div>
    );
}

Index.layout = (page) => <StudentLayout title={page.props.page_settings.titel} children={page} />;
