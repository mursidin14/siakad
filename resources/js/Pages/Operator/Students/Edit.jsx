import HeaderTitle from '@/Components/HeaderTitle';
import InputError from '@/Components/InputError';
import { Button } from '@/Components/ui/button';
import { Card, CardContent } from '@/Components/ui/card';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import AppLayout from '@/Layouts/AppLayout';
import { flashMessage } from '@/lib/utils';
import { Link, useForm } from '@inertiajs/react';
import { IconArrowLeft, IconCheck, IconUser } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Edit(props) {
    const fileInputAvatar = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        class_room_id: props.student.class_room_id ?? null,
        fee_group_id: props.student.fee_group_id ?? null,
        student_number: props.student.student_number ?? '',
        name: props.student.user.name ?? '',
        email: props.student.user.email ?? '',
        password: '',
        avatar: null,
        semester: props.student.semester ?? 1,
        batch: props.student.batch ?? '',
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
    };

    return (
        <div className="flex w-full flex-col pb-32">
            <div className="mb-8 flex flex-col items-start justify-between gap-y-4 lg:flex-row lg:items-center">
                <HeaderTitle
                    title={props.page_settings.title}
                    subtitle={props.page_settings.subtitle}
                    icon={IconUser}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('operator.students.index')}>
                        <IconArrowLeft className="size-4" />
                        Kembali
                    </Link>
                </Button>
            </div>

            <Card>
                <CardContent className="p-6">
                    <form onSubmit={onHandleSubmit}>
                        <div className="grid grid-cols-1 gap-4">
                            <div className="col-span-full">
                                <Label htmlFor="name">Nama</Label>
                                <Input
                                    type="text"
                                    name="name"
                                    id="name"
                                    placeholder="Masukan nama mahasiswa"
                                    value={data.name}
                                    onChange={onHandleChange}
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="Masukan alamat email mahasiswa"
                                    value={data.email}
                                    onChange={onHandleChange}
                                />
                                {errors.email && <InputError message={errors.email} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="password">Password</Label>
                                <Input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="******"
                                    value={data.password}
                                    onChange={onHandleChange}
                                />
                                {errors.password && <InputError message={errors.password} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="class_room_id">Kelas</Label>
                                <Select
                                    defaultValue={data.class_room_id}
                                    onValueChange={(value) => setData('class_room_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.classRooms.find((classroom) => classroom.value == data.class_room_id)
                                                ?.label ?? 'Pilih Kelas'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.classRooms.map((classroom, index) => (
                                            <SelectItem key={index} value={classroom.value}>
                                                {classroom.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.class_room_id && <InputError message={errors.class_room_id} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="fee_group_id">Golongan Ukt</Label>
                                <Select
                                    defaultValue={data.fee_group_id}
                                    onValueChange={(value) => setData('fee_group_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.feeGroups.find((feeGroup) => feeGroup.value == data.fee_group_id)
                                                ?.label ?? 'Pilih Golongan Ukt'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.feeGroups.map((feeGroup, index) => (
                                            <SelectItem key={index} value={feeGroup.value}>
                                                {feeGroup.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.fee_group_id && <InputError message={errors.fee_group_id} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="student_number">Nomor Induk Mahasiswa</Label>
                                <Input
                                    type="text"
                                    name="student_number"
                                    id="student_number"
                                    placeholder="Masukan nim mahasiswa"
                                    value={data.student_number}
                                    onChange={onHandleChange}
                                />
                                {errors.student_number && <InputError message={errors.student_number} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="semester">Semester</Label>
                                <Input
                                    type="number"
                                    name="semester"
                                    id="semester"
                                    placeholder="Masukan semester mahasiswa"
                                    value={data.semester}
                                    onChange={onHandleChange}
                                />
                                {errors.semester && <InputError message={errors.semester} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="batch">Angkatan</Label>
                                <Input
                                    type="text"
                                    name="batch"
                                    id="batch"
                                    placeholder="Masukan angkatan mahasiswa"
                                    value={data.batch}
                                    onChange={onHandleChange}
                                />
                                {errors.batch && <InputError message={errors.batch} />}
                            </div>
                        </div>

                        <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                            <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                                Reset
                            </Button>
                            <Button type="submit" variant="blue" size="xl" disabled={processing}>
                                <IconCheck />
                                Save
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
}

Edit.layout = (page) => <AppLayout title={page.props.page_settings.title} children={page} />;
