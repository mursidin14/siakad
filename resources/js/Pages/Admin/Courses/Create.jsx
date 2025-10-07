import HeaderTitle from '@/Components/HeaderTitle'
import InputError from '@/Components/InputError'
import { Button } from '@/Components/ui/button'
import { Card, CardContent } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select'
import AppLayout from '@/Layouts/AppLayout'
import { flashMessage } from '@/lib/utils'
import { Link, useForm } from '@inertiajs/react'
import { IconArrowLeft, IconBook, IconUser } from '@tabler/icons-react'
import React, { useRef } from 'react'
import { toast } from 'sonner'

export default function Create(props) {

    const fileInputAvatar = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        faculty_id: null,
        departement_id: null,
        teacher_id: null,
        academic_year_id: null,
        name: '',
        code: '',
        credits: 0,
        semester: 1,
        _method: props.page_settings.method,
    });

    const onHandleChange = (e) => setData(e.target.name, e.target.value);

    const onHandleReset = () => {
        reset();
        fileInputAvatar.current.value = null;
    };

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if (flash) toast[flash.type](flash.message);
            },
        });
    }


  return (
        <div className='flex w-full flex-col pb-32'>
            <div className='mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center'>
                <HeaderTitle 
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBook}
                />
                <Button
                    variant='orange'
                    size='xl'
                    className='w-full lg:w-auto'
                    asChild
                >
                    <Link href={route('admin.courses.index')}>
                        <IconArrowLeft className='size-4' />
                        Kembali
                    </Link>
                </Button>
            </div>

            <Card>
                <CardContent className='p-6'>
                    <form onSubmit={onHandleSubmit}>
                        <div className='grid grid-cols-1 gap-4'>
                            <div className='col-span-full'>
                                <Label htmlFor='name'>Nama</Label>
                                <Input 
                                    type='text'
                                    name='name'
                                    id='name'
                                    placeholder='Masukan nama mata kuliah'
                                    value={data.name}
                                    onChange={onHandleChange}
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='code'>Kode</Label>
                                <Input 
                                    type='text'
                                    name='code'
                                    id='code'
                                    placeholder='Masukan kode mata kuliah'
                                    value={data.code}
                                    onChange={onHandleChange}
                                />
                                {errors.code && <InputError message={errors.code} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='credits'>SKS</Label>
                                <Input 
                                    type='number'
                                    name='credits'
                                    id='credits'
                                    placeholder='Masukkan jumlah SKS'
                                    value={data.credits}
                                    onChange={onHandleChange}
                                />
                                {errors.credits && <InputError message={errors.credits} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='semester'>Semester</Label>
                                <Input 
                                    type='number'
                                    name='semester'
                                    id='semester'
                                    placeholder='Masukkan semester'
                                    value={data.semester}
                                    onChange={onHandleChange}
                                />
                                {errors.semester && <InputError message={errors.semester} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='faculty_id'>Fakultas</Label>
                                <Select
                                    defaultValue={data.faculty_id}
                                    onValueChange={(value) => setData('faculty_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.faculties.find((faculty) => faculty.value == data.faculty_id)
                                                ?.label ?? 'Pilih Fakultas'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.faculties.map((faculty, index) => (
                                            <SelectItem key={index} value={faculty.value}>
                                                {faculty.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.faculty_id && <InputError message={errors.faculty_id} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='departement_id'>Program Studi</Label>
                                <Select
                                    defaultValue={data.departement_id}
                                    onValueChange={(value) => setData('departement_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.departements.find((departement) => departement.value == data.departement_id)
                                                ?.label ?? 'Pilih Program Studi'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.departements.map((departement, index) => (
                                            <SelectItem key={index} value={departement.value}>
                                                {departement.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.departement_id && <InputError message={errors.departement_id} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='teacher_id'>Nama Dosen</Label>
                                <Select
                                    defaultValue={data.teacher_id}
                                    onValueChange={(value) => setData('teacher_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.teachers.find((teacher) => teacher.value == data.teacher_id)
                                                ?.label ?? 'Pilih Dosen'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.teachers.map((teacher, index) => (
                                            <SelectItem key={index} value={teacher.value}>
                                                {teacher.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.teacher_id && <InputError message={errors.teacher_id} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='academic_year_id'>Tahun Ajaran</Label>
                                <Select
                                    defaultValue={data.academic_year_id}
                                    onValueChange={(value) => setData('academic_year_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.academicYears.find((academicYear) => academicYear.value == data.academic_year_id)
                                                ?.label ?? 'Pilih Tahun Ajaran'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.academicYears.map((academicYear, index) => (
                                            <SelectItem key={index} value={academicYear.value}>
                                                {academicYear.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.academic_year_id && <InputError message={errors.academic_year_id} />}
                            </div>
                        </div>

                        <div className='mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end'>
                            <Button type='button' variant='ghost' size='xl'
                                onClick={onHandleReset}
                            >
                                Reset
                            </Button>
                            <Button type='submit' variant='blue' size='xl' disabled={processing}>
                                Save
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
  );
};

Create.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />
