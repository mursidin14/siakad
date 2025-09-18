import Banner from '@/Components/Banner';
import { Card, CardContent } from '@/Components/ui/card';
import { Toaster } from '@/Components/ui/toaster';
import { flashMessage } from '@/lib/utils';
import { Head, usePage } from '@inertiajs/react';
import { useEffect } from 'react';
import { toast } from 'sonner';
import HeaderStudentLayout from './Partials/HeaderStudentLayout';

export default function StudentLayout({ title, children }) {
    const checkFee = usePage().props.checkFee;

    const flash = flashMessage(usePage());

    const { url } = usePage();

    useEffect(() => {
        if (flash && flash.message && flash.type === 'warning') {
            toast[flash.type](flash.message);
        }
    }, [flash]);

    return (
        <>
            <Head title={title} />

            <Toaster position="top-center" richColors />

            <div className="min-h-full">
                <div className="bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 pb-32">
                    {/* header layout */}
                    <HeaderStudentLayout url={url} />
                </div>

                <main className="-mt-32 px-6 pb-12 lg:px-28">
                    <Card>
                        <CardContent className="p-6">{children}</CardContent>
                    </Card>
                    {/* check fee */}

                    {checkFee && <Banner message={'Harap melakukan pembayaran uang tunggal terlebih dahulu'} />}
                </main>
            </div>
        </>
    );
}
