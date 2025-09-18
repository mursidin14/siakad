import StudentLayout from '@/Layouts/StudentLayout';

export default function Dashboard(props) {
    return (
        <div className="flex flex-col gap-8">
            <div className="flex flex-col items-center justify-between gap-y-4 lg:flex-row">
                <div>
                    <h3 className="text-xl font-semibold leading-relaxed tracking-tight text-foreground">
                        {props.page_settings.title}
                    </h3>
                    <p className="text-sm text-muted-foreground">{props.page_settings.subtitle}</p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia quod tempore expedita accusantium
                    nobis nam libero hic quo incidunt, ullam perferendis iusto tempora. Obcaecati suscipit officia
                    numquam minima ut qui! Sunt asperiores ab eaque delectus a blanditiis impedit excepturi dignissimos
                    consequatur. Dolore modi velit fugiat aliquid earum quis beatae quaerat, quam omnis soluta odit
                    maiores repellat sequi eius tempore dolores. Cum minus, repellendus consectetur modi asperiores
                    reiciendis excepturi earum blanditiis, dolorum deleniti harum aliquam, nostrum quos distinctio est
                    cupiditate minima aperiam quis provident vitae molestias? Nam aut perspiciatis iure voluptatibus?
                </div>
            </div>
        </div>
    );
}

Dashboard.layout = (page) => <StudentLayout title={page.props.page_settings.title} children={page} />;
