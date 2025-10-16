import HeaderTitle from '@/Components/HeaderTitle'
import { Alert, AlertDescription } from '@/Components/ui/alert'
import { Badge } from '@/Components/ui/badge'
import { Button } from '@/Components/ui/button'
import { Checkbox } from '@/Components/ui/checkbox'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table'
import StudentLayout from '@/Layouts/StudentLayout'
import { cn, flashMessage, STUDYPLANSTATUSVARIANT, STUDYPLANTSTATUS } from '@/lib/utils'
import { Link, useForm } from '@inertiajs/react'
import { IconArrowBack, IconBuilding } from '@tabler/icons-react'
import React from 'react'
import { toast } from 'sonner'

export default function Show(props) {

  return (
    <div className='flex w-full flex-col pb-32'>
        <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuilding}
                />

                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('student.study-plans.index')}>
                        <IconArrowBack className="size-4" />
                        Kembali
                    </Link>
                </Button>
         </div>
         <div className='flex flex-col gap-y-8'>
            {props.studyPlan.status === STUDYPLANTSTATUS.REJECTED && (
                <Alert variant="destructive">
                    <AlertDescription>
                        {props.studyPlan.notes}
                    </AlertDescription>
                </Alert>
            )}
            <Table className="w-full">
                <TableHeader>
                    <TableRow>
                        <TableHead>#</TableHead>
                        <TableHead>Mata Kuliah</TableHead>
                        <TableHead>SKS</TableHead>
                        <TableHead>Kelas</TableHead>
                        <TableHead>Tahun Ajaran</TableHead>
                        <TableHead>Waktu</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {props.studyPlan.schedules.map((schedule, index) => (
                        <TableRow key={index}>
                            <TableCell>{index + 1}</TableCell>
                            <TableCell>{schedule.course.name}</TableCell>
                            <TableCell>{schedule.course.credits}</TableCell>
                            <TableCell>{schedule.classRoom.name}</TableCell>
                            <TableCell>{schedule.academicYear.name}</TableCell>
                            <TableCell>{schedule.day_of_week}, {schedule.start_time} - {schedule.end_time}</TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
            <div className="flex w-full flex-col items-center justify-between py-2 lg:flex-row">
                <p className="text-sm text-muted-foreground">
                    Tahun Ajaran: <span className="font-bold text-blue-600">{props.studyPlan.academicYear.name}</span>
                </p>
                <p className="text-sm text-muted-foreground">
                    Status: <Badge variant={STUDYPLANSTATUSVARIANT[props.studyPlan.status]}>{props.studyPlan.status}</Badge>
                </p>
            </div>
         </div>
    </div>
  )
}


Show.layout = (page) => <StudentLayout title={page.props.page_settings.title} children={page}/>
