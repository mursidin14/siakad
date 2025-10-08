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
import { IconArrowLeft, IconCalendar, IconCheck } from '@tabler/icons-react';
import { useRef } from 'react';
import { toast } from 'sonner';

export default function Edit(props) {

    const { data, setData, post, processing, errors, reset } = useForm({
        faculty_id: props.schedule.faculty_id ?? null,
        departement_id: props.schedule.departement_id ?? null,
        class_room_id: props.schedule.class_room_id ?? null,
        course_id: props.schedule.course_id ?? null,
        academic_year_id: props.schedule.academic_year_id ?? null,
        start_time: props.schedule.start_time ?? '',
        end_time: props.schedule.end_time ?? '',
        day_of_week: props.schedule.day_of_week ?? null,
        quota: props.schedule.quota ?? 1,
        _method: props.page_settings.method,
    });

    const onHandleChange = (e) => setData(e.target.name, e.target.value);

    const onHandleReset = () => {
        reset();
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
                    icon={IconCalendar}
                />
                <Button variant="orange" size="xl" className="w-full lg:w-auto" asChild>
                    <Link href={route('admin.schedules.index')}>
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
                                <Label htmlFor="faculty_id">Fakultas</Label>
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

                            <div className="col-span-full">
                                <Label htmlFor="departement_id">Program Studi</Label>
                                <Select
                                    defaultValue={data.departement_id}
                                    onValueChange={(value) => setData('departement_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.departements.find(
                                                (departement) => departement.value == data.departement_id,
                                            )?.label ?? 'Pilih Program Studi'}
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

                            <div className="col-span-full">
                                <Label htmlFor="class_room_id">Kelas</Label>
                                <Select
                                    defaultValue={data.class_room_id}
                                    onValueChange={(value) => setData('class_room_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.classrooms.find((classroom) => classroom.value == data.class_room_id)
                                                ?.label ?? 'Pilih Kelas'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.classrooms.map((classroom, index) => (
                                            <SelectItem key={index} value={classroom.value}>
                                                {classroom.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.class_room_id && <InputError message={errors.class_room_id} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="course_id">Mata Kuliah</Label>
                                <Select
                                    defaultValue={data.course_id}
                                    onValueChange={(value) => setData('course_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.courses.find((course) => course.value == data.course_id)
                                                ?.label ?? 'Pilih Mata Kuliah'}
                                        </SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        {props.courses.map((course, index) => (
                                            <SelectItem key={index} value={course.value}>
                                                {course.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                {errors.course_id && <InputError message={errors.course_id} />}
                            </div>

                            <div className="col-span-full grid grid-cols-1 gap-4 lg:grid-cols-3">
                                <div>
                                    <Label htmlFor="start_time">Waktu Mulai</Label>
                                    <Input
                                        type="time"
                                        name="start_time"
                                        id="start_time"
                                        placeholder="Masukan waktu mulai"
                                        value={data.start_time}
                                        onChange={onHandleChange}
                                    />
                                    {errors.start_time && <InputError message={errors.start_time} />}
                                </div>
                                <div>
                                    <Label htmlFor="end_time">Waktu Selesai</Label>
                                    <Input
                                        type="time"
                                        name="end_time"
                                        id="end_time"
                                        placeholder="Masukan waktu selesai"
                                        value={data.end_time}
                                        onChange={onHandleChange}
                                    />
                                    {errors.end_time && <InputError message={errors.end_time} />}
                                </div>
                                <div>
                                    <Label htmlFor="day_of_week">Hari</Label>
                                    <Select
                                        defaultValue={data.day_of_week}
                                        onValueChange={(value) => setData('day_of_week', value)}
                                    >
                                        <SelectTrigger>
                                            <SelectValue>
                                                {props.days.find((day) => day.value == data.day_of_week)
                                                    ?.label ?? 'Pilih Hari'}
                                            </SelectValue>
                                        </SelectTrigger>
                                        <SelectContent>
                                            {props.days.map((day, index) => (
                                                <SelectItem key={index} value={day.value}>
                                                    {day.label}
                                                </SelectItem>
                                            ))}
                                        </SelectContent>
                                    </Select>
                                    {errors.day_of_week && <InputError message={errors.day_of_week} />}
                            </div>
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="quota">Kuota</Label>
                                <Input
                                    type="number"
                                    name="quota"
                                    id="quota"
                                    placeholder="Masukan kuota"
                                    value={data.quota}
                                    onChange={onHandleChange}
                                />
                                {errors.quota && <InputError message={errors.quota} />}
                            </div>

                            <div className="col-span-full">
                                <Label htmlFor="academic_year_id">Mata Kuliah</Label>
                                <Select
                                    defaultValue={data.academic_year_id}
                                    onValueChange={(value) => setData('academic_year_id', value)}
                                >
                                    <SelectTrigger>
                                        <SelectValue>
                                            {props.academicYears.find((year) => year.value == data.academic_year_id)
                                                ?.label ?? 'Pilih Tahun Akademik'}
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
