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
import { IconArrowLeft, IconUser } from '@tabler/icons-react'
import React, { useRef } from 'react'
import { toast } from 'sonner'

export default function Create(props) {

    const fileInputAvatar = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        faculty_id: null,
        departement_id: null,
        employee_number: '',
        name: '',
        email: '',
        password: '',
        avatar: null,
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
                    icon={IconUser}
                />
                <Button
                    variant='orange'
                    size='xl'
                    className='w-full lg:w-auto'
                    asChild
                >
                    <Link href={route('admin.operators.index')}>
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
                                    placeholder='Masukan nama karyawan'
                                    value={data.name}
                                    onChange={onHandleChange}
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='email'>Email</Label>
                                <Input 
                                    type='email'
                                    name='email'
                                    id='email'
                                    placeholder='Masukan email karyawan'
                                    value={data.email}
                                    onChange={onHandleChange}
                                />
                                {errors.email && <InputError message={errors.email} />}
                            </div>

                            <div className='col-span-full'>
                                <Label htmlFor='password'>Password</Label>
                                <Input 
                                    type='password'
                                    name='password'
                                    id='password'
                                    placeholder='******'
                                    value={data.password}
                                    onChange={onHandleChange}
                                />
                                {errors.password && <InputError message={errors.password} />}
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
                                <Label htmlFor='employee_number'>Nomor Karyawan</Label>
                                <Input 
                                    type='text'
                                    name='employee_number'
                                    id='employee_number'
                                    placeholder='Masukan NiK karyawan'
                                    value={data.employee_number}
                                    onChange={onHandleChange}
                                />
                                {errors.employee_number && <InputError message={errors.employee_number} />}
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
