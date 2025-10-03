import AlertAction from '@/Components/AlertAction';
import ComboBox from '@/Components/ComboBox';
import EmptyState from '@/Components/EmptyState';
import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardHeader } from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import AppLayout from '@/Layouts/AppLayout'
import { deleteAction, flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCheck, IconDoor } from '@tabler/icons-react';
import React from 'react'
import { toast } from 'sonner';

export default function Index(props) {

    const {data: classroomStudents} = props.classroomStudents;
    
    const {data, setData, post, processing, errors} = useForm({
        student: null,
        _method: props.page_settings.method
    });


    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {
                const flash = flashMessage(success);
                if(flash) toast[flash.type](flash.message);
            },
        });
    }

  return (
        <div className='flex w-full flex-col pb-32'>
            <div className='mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center'>
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconDoor}
                />

                <Button variant='orange' size='xl' className='w-full lg:w-auto' asChild>
                    <Link href={route('admin.classrooms.index')}>
                        <IconArrowLeft className='size-4' />
                        Kembali
                    </Link>
                </Button>
            </div>
            <Card>
                <CardHeader className='mb-4'>
                    <form onSubmit={onHandleSubmit}>
                    <div className='grid grid-cols-1 gap-4 lg:grid-cols-4'>
                        <div className='col-span-full'>
                            <Label htmlFor='student'>Mahasiswa</Label>
                            <ComboBox
                                items={props.students}
                                selectedItem={data.student}
                                placeholder='Pilih Mahasiswa'
                                onSelect={(currentValue) => setData('student', currentValue)}
                            />
                            {errors.student && <InputError message={errors.student} />}
                        </div>
                    </div>

                    <div className='flex flex-col gap-2 mt-8 lg:flex-row lg:justify-end'>
                        <Button variant='blue' size='xl' type='submit' disabled={processing}>
                            <IconCheck className='size-4' />
                            Save
                        </Button>
                    </div>
                </form>
                </CardHeader>
                <CardContent>
                    {classroomStudents.length === 0 ? (
                        <EmptyState 
                            icon={IconDoor}
                            title="Tidak ada mahasiswa"
                            subtitle="Mulailah dengan memasukkan mahasiswa kedalam kelas"
                        />
                    ): (
                        <div className='grid grid-cols-4 gap-4'>
                            {Array.from({length: 20}).map((_, index) => {
                                const student = classroomStudents[index];
                                return student ? (
                                     <AlertAction 
                                        key={index}
                                        trigger={
                                            <Button size="xl" variant="green" className="p-16">
                                                <div className='flex flex-col items-center gap-y-3'>
                                                    <Avatar>
                                                        <AvatarImage src={student.user.avatar} />
                                                        <AvatarFallback>{student.user.name.substring(0, 1)}</AvatarFallback>
                                                    </Avatar>
                                                    <div className='flex flex-col'>
                                                        <span className='text-base font-semibold truncate'>{student.user.name}</span>
                                                        <span className='text-sm'>{student.student_number}</span>
                                                    </div>
                                                </div>
                                            </Button>
                                        }
                                        action={() => deleteAction(route('admin.classroom-students.destroy', [props.classroom, student]))}
                                     />
                                ):(
                                    <Button variant="outline" size="xl" className="p-16" key={index}>
                                        <div className='flex flex-col items-center gap-y-3'>
                                            <div className='flex flex-col'>
                                                <span className='text-base font-semibold truncate'>{index + 1}</span>
                                            </div>
                                        </div>
                                    </Button>
                                )
                            })}
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
  )
}

Index.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />
