import { Link } from '@inertiajs/react';

export default function NavLink({
    active = false,
    url = '#',
    title,
    icon: Icon,
    ...props
}) {
    return (
        <Link
            {...props}
            href={url}
            className={cn(
                active ? 'bg-blue-800' : 'hover:bg-blue-800',
                'my-1 flex items-center gap-3 rounded-lg font-medium text-white transition-all',
                props.className
            )}
        >
            <Icon className="size-6" />
        </Link>
    );
}
