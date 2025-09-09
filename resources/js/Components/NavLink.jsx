import { Link } from '@inertiajs/react';
import { cn } from '@/lib/utils';

export default function NavLink({ active = false, url = '#', title, icon: Icon, ...props }) {
    return (
        <Link
            {...props}
            href={url}
            className={cn(
                active ? 'bg-blue-800' : 'hover:bg-blue-800',
                'my-1 flex items-center gap-3 rounded-lg font-medium text-white transition-all p-3',
                props.className,
            )}
        >
            <Icon className="size-6" />
            {title}
        </Link>
    );
}
