import HeaderTitle from '@/Components/HeaderTitle'
import InputError from '@/Components/InputError'
import { Button } from '@/Components/ui/button'
import { Card, CardContent } from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import AppLayout from '@/Layouts/AppLayout'
import { flashMessage } from '@/lib/utils'
import { Link, useForm } from '@inertiajs/react'
import { IconArrowLeft, IconBuildingSkyscraper, IconCheck } from '@tabler/icons-react'
import React, { useRef } from 'react'
import { toast } from 'sonner'

export default function Create(props) {

    const fileInputLogo = useRef(null);

    const {data, setData, post, processing, errors, reset} = useForm({
        name: '',
        logo: null,
        _method: props.page_settings.method,
    
    });

    const onHandleReset = () => {
        reset();
        fileInputLogo.current.value = null;
    }

    const onHandleSubmit = (e) => {
        e.preventDefault();
        post(props.page_settings.action, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (success) => {

                const flash = flashMessage(success);
                if(flash) toast[flash.type](flash.message);
            }
        })
        
    }

    
  return (
        <div className='flex flex-col w-full pb-32'>
            <div className='flex flex-col items-start justify-between mb-8 gap-y-4 lg:flex-row lg:items-center'>
                <HeaderTitle 
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconBuildingSkyscraper}
                />
                <Button
                    variant='orange'
                    size='xl'
                    className='w-full lg:w-auto'
                    asChild
                >
                    <Link href={route('admin.faculties.index')}>
                        <IconArrowLeft className='size-4' />
                        Kembali
                    </Link>
                </Button>
            </div>

            <Card>
                <CardContent className='p-6'>
                    <form onSubmit={onHandleSubmit}>
                        <div className='grid grid-cols-1 gap-4 '>
                            <div className='col-span-full'>
                                <label htmlFor="name">Nama</label>
                                <Input 
                                    type="text"
                                    name="name"
                                    id='name'
                                    placeholder='Masukan nama fakultas'
                                    value={data.name}
                                    onChange={(e) => setData(e.target.name, e.target.value)}
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>

                            <div className='col-span-full'>
                                <label htmlFor="logo">Logo</label>
                                <Input 
                                    type="file"
                                    name="logo"
                                    id='logo'
                                    onChange={(e) => setData(e.target.name, e.target.files[0])}
                                    ref={fileInputLogo}
                                />
                                {errors.logo && <InputError message={errors.logo} />}
                            </div>
                        </div>

                        <div className='flex flex-col gap-2 mt-8 lg:flex-row lg:justify-end'>
                            <Button type='button' variant='ghost' size='xl' onClick={onHandleReset}>
                                Reset
                            </Button>
                            <Button type="submit" variant='blue' size='xl' disabled={processing}>
                                <IconCheck />
                                Save
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
  )
}

Create.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />