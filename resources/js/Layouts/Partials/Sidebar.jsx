import NavLink from '@/Components/NavLink';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Link } from '@inertiajs/react';
import {
    IconBook,
    IconBuildingSkyscraper,
    IconCalendar,
    IconCalendarTime,
    IconCircleKey,
    IconDoor,
    IconDroplets,
    IconLayout2,
    IconLogout2,
    IconMoneybag,
    IconSchool,
    IconUser,
    IconUsersGroup,
} from '@tabler/icons-react';

export default function Sidebar({ url, auth }) {
    return (
        <nav className="flex flex-1 flex-col">
            <ul role="list" className="flex flex-1 flex-col">
                <li className="-mx-6">
                    <Link
                        href={'#'}
                        className="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold leading-6 text-white hover:bg-blue-800"
                    >
                        <Avatar>
                            <AvatarImage src={auth.avatar} />
                            <AvatarFallback>{auth.name.substring(0, 1)}</AvatarFallback>
                        </Avatar>

                        <div className="flex flex-col text-left">
                            <span className="truncate font-bold">{auth.name}</span>
                            <span className="truncate">{auth.role_name}</span>
                        </div>
                    </Link>
                </li>

                {/* role admin */}
                {auth.roles.some((role) => ['Admin'].includes(role)) && (
                    <>
                        <NavLink
                            url={route('admin.dashboard')}
                            active={url.startsWith('/admin/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Master</div>

                        <NavLink
                            url={route('admin.faculties.index')}
                            active={url.startsWith('/admin/faculties')}
                            title={'Faculties'}
                            icon={IconBuildingSkyscraper}
                        />
                        <NavLink
                            url={route('admin.departements.index')}
                            active={url.startsWith('/admin/departements')}
                            title={'Program Studi'}
                            icon={IconSchool}
                        />
                        <NavLink
                            url={route('admin.academic-years.index')}
                            active={url.startsWith('/admin/academic-years')}
                            title={'Tahun Ajaran'}
                            icon={IconCalendarTime}
                        />
                        <NavLink
                            url={route('admin.classrooms.index')}
                            active={url.startsWith('/admin/classrooms')}
                            title={'Kelas'}
                            icon={IconDoor}
                        />
                        <NavLink
                            url={route('admin.roles.index')}
                            active={url.startsWith('/admin/roles')}
                            title={'Peran'}
                            icon={IconCircleKey}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Pengguna</div>
                        <NavLink
                            url={route('admin.students.index')}
                            active={url.startsWith('/admin/students')}
                            title={'Mahasiswa'}
                            icon={IconUser}
                        />
                        <NavLink
                            url={route('admin.teachers.index')}
                            active={url.startsWith('/admin/teachers')}
                            title={'Dosen'}
                            icon={IconUsersGroup}
                        />
                        <NavLink
                            url={route('admin.operators.index')}
                            active={url.startsWith('/admin/operators')}
                            title={'Operator'}
                            icon={IconUser}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Akademik</div>
                        <NavLink
                            url={route('admin.courses.index')}
                            active={url.startsWith('/admin/courses')}
                            title={'Mata Kuliah'}
                            icon={IconBook}
                        />
                        <NavLink
                            url={route('admin.schedules.index')}
                            active={url.startsWith('/admin/schedules')}
                            title={'Jadwal Kuliah'}
                            icon={IconCalendar}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Pembayaran</div>
                        <NavLink
                            url={route('admin.fees.index')}
                            active={url.startsWith('/admin/fees')}
                            title={'Uang Kuliah Tunggal'}
                            icon={IconMoneybag}
                        />
                        <NavLink
                            url={route('admin.fee-groups.index')}
                            active={url.startsWith('/admin/fee-groups')}
                            title={'Golongan UKT'}
                            icon={IconDroplets}
                        />
                    </>
                )}

                {/* role teacher */}
                {auth.roles.some((role) => ['Teacher'].includes(role)) && (
                    <>
                        <NavLink
                            url="#"
                            active={url.startsWith('/teacher/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Akademik</div>
                        <NavLink
                            url="#"
                            active={url.startsWith('/teacher/courses')}
                            title={'Mata Kuliah'}
                            icon={IconBook}
                        />
                        <NavLink
                            url="#"
                            active={url.startsWith('/teacher/schedules')}
                            title={'Jadwal Kuliah'}
                            icon={IconCalendar}
                        />
                    </>
                )}

                {/* role operator */}
                {auth.roles.some((role) => ['Operator'].includes(role)) && (
                    <>
                        <NavLink
                            url="#"
                            active={url.startsWith('/teacher/dashboard')}
                            title={'Dashboard'}
                            icon={IconLayout2}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Pengguna</div>
                        <NavLink
                            url="#"
                            active={url.startsWith('/operator/students')}
                            title={'Mahasiswa'}
                            icon={IconUser}
                        />
                        <NavLink
                            url="#"
                            active={url.startsWith('/operator/teachers')}
                            title={'Dosen'}
                            icon={IconUsersGroup}
                        />

                        <div className="px-3 py-2 text-xs font-medium text-white">Akademik</div>
                        <NavLink
                            url="#"
                            active={url.startsWith('/operator/classrooms')}
                            title={'Kelas'}
                            icon={IconDoor}
                        />
                        <NavLink
                            url="#"
                            active={url.startsWith('/operator/courses')}
                            title={'Mata Kuliah'}
                            icon={IconBook}
                        />
                        <NavLink
                            url="#"
                            active={url.startsWith('/admin/schedules')}
                            title={'Jadwal Kuliah'}
                            icon={IconCalendar}
                        />
                    </>
                )}

                <div className="px-3 py-2 text-xs font-medium text-white">Lainnya</div>
                <NavLink
                    url={route('logout')}
                    method="post"
                    as="button"
                    active={url.startsWith('/logout')}
                    title={'Logout'}
                    icon={IconLogout2}
                />
            </ul>
        </nav>
    );
}
