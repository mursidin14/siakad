import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { Head, Link } from '@inertiajs/react'
import { IconCircleCheck } from '@tabler/icons-react'
import React from 'react'

export default function Success() {
  return (
    <>
        <Head title='Pembayaran Sukses' />
        <div className='flex min-h-screen items-center justify-center'>
            <div className='mx-auto max-w-sm'>
                <Card>
                    <CardHeader className='flex flex-row items-center gap-x-2'>
                        <IconCircleCheck className='text-green-500' />
                        <div>
                            <CardTitle>Pembayaran Berhasil</CardTitle>
                            <CardDescription>Pembayaran telah berhasil di proses</CardDescription>
                        </div>
                    </CardHeader>
                    <CardContent className='flex flex-col gap-y-6'>
                        <p className='text-start text-foreground'>Terimakasih telah melakukan pembayaran UKT, Pembayaran anda berhasil di proses.</p>
                        <Button variant='orange' asChild>
                            <Link href={route('dashboard')}>
                                Kembali ke Dashboard
                            </Link>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </>
  )
}
