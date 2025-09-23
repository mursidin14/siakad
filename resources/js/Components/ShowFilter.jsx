import { cn } from '@/lib/utils';
import { IconFilter } from '@tabler/icons-react';
import { Badge } from './ui/badge';
import { Separator } from './ui/separator';

export default function ShowFilter({ params, className = '' }) {
    const hasFilters = Object.values(params).some((value) => value);

    return (
        <>
            {hasFilters && (
                <div className={cn(`flex w-full flex-wrap gap-y-2 bg-secondary p-3 ${className}`)}>
                    <span className="flex items-center gap-1 text-sm">
                        <IconFilter className="size-4" />
                        Filters:
                    </span>
                    <Separator orientation="vertical" className="mx-2" />
                    {Object.entries(params).map(
                        ([key, value]) =>
                            value && (
                                <Badge key={key} variant="white" className="mx-2 h-6">
                                    {key.charAt(0).toUpperCase() + key.slice(1)} : {value}
                                </Badge>
                            ),
                    )}
                </div>
            )}
        </>
    );
}
