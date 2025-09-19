import CardStat from '@/Components/CardStat';
import HeaderTitle from '@/Components/HeaderTitle';
import AppLayout from '@/Layouts/AppLayout';
import { usePage } from '@inertiajs/react';
import { IconBook2, IconCalendar, IconDoor, IconLayout2 } from '@tabler/icons-react';

export default function Dashboard(props) {

    const auth = usePage().props.auth.user;

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconLayout2}
                />
            </div>

            <div className='flex flex-col mb-8'>
                <h2 className='text-xl font-medium leading-relaxed text-foreground'>Hi, {auth.name}</h2>
                <p className='text-sm text-muted-foreground'>Selamat datang di Sistem Informasi Akademik Universitas</p>
            </div>

            <div className='grid gap-4 mb-8 lg:grid-cols-4'>
                {/* card stat Course */}
                <CardStat
                    data={{
                        title: 'Mata Kuliah',
                        icon: IconBook2,
                        background: 'text-white bg-gradient-to-r from-red-400 via-red-500 to-red-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className='text-2xl font-bold'>{props.count.courses}</div>
                </CardStat>

                {/* card stat Class Room */}
                <CardStat
                    data={{
                        title: 'Total Kelas',
                        icon: IconDoor,
                        background: 'text-white bg-gradient-to-r from-orange-400 via-orange-500 to-orange-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className='text-2xl font-bold'>{props.count.classroom}</div>
                </CardStat>

                {/* card stat Jadwal Kelas */}
                <CardStat
                    data={{
                        title: 'Jadwal Kelas',
                        icon: IconCalendar,
                        background: 'text-white bg-gradient-to-r from-lime-400 via-lime-500 to-lime-500',
                        iconClassName: 'text-white',
                    }}
                >
                    <div className='text-2xl font-bold'>{props.count.schedules}</div>
                </CardStat>
            </div>

        </div>
    );
}

Dashboard.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
