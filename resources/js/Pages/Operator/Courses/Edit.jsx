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
import { IconArrowLeft, IconBook } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Edit(props) {
    const fileInputAvatar = useRef(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        teacher_id: props.course.teacher_id ?? null,
        academic_year_id: props.course.academic_year_id ?? null,
        name: props.course.name ?? '',
        code: props.course.code ?? '',
        credits: props.course.credits ?? 0,
        semester: props.course.semester ?? 1,
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
                    icon={IconBook}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.courses.index')}>
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
                                    placeholder="Masukan nama mata kuliah"
                                    value={data.name}
                                    onChange={onHandleChange}
                                />
                                {errors.name && <InputError message={errors.name} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="code">Kode</Label>
                                <Input
                                    type="text"
                                    name="code"
                                    id="code"
                                    placeholder="Masukan kode mata kuliah"
                                    value={data.code}
                                    onChange={onHandleChange}
                                />
                                {errors.code && <InputError message={errors.code} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="credits">SKS</Label>
                                <Input
                                    type="number"
                                    name="credits"
                                    id="credits"
                                    placeholder="Masukkan jumlah SKS"
                                    value={data.credits}
                                    onChange={onHandleChange}
                                />
                                {errors.credits && <InputError message={errors.credits} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="semester">Semester</Label>
                                <Input
                                    type="number"
                                    name="semester"
                                    id="semester"
                                    placeholder="Masukkan semester"
                                    value={data.semester}
                                    onChange={onHandleChange}
                                />
                                {errors.semester && <InputError message={errors.semester} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="teacher_id">Nama Dosen</Label>
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
                        </div>

                        <div className="mt-8 flex flex-col gap-2 lg:flex-row lg:justify-end">
                            <Button type="button" variant="ghost" size="xl" onClick={onHandleReset}>
                                Reset
                            </Button>
                            <Button type="submit" variant="blue" size="xl" disabled={processing}>
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
