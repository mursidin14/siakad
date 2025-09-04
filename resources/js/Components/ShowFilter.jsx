import { cn } from '@/lib/utils'
import { IconFilter } from '@tabler/icons-react'
import React from 'react'
import { Separator } from './ui/separator'
import { Badge } from './ui/badge'

export default function ShowFilter({params, className=''}) {
  return (
    <div>
        {Object.keys(params).some((key) => params[key] && (
            <div className={cn(`flex w-full flex-wrap gap-y-2 bg-secondary p-3 ${className}`)}>
                <span className='flex items-center gap-1 text-sm'>
                    <IconFilter className="size-4" />
                    Filters:
                </span>
                <Separator orientation='vertical' className='mx-2' />
                {Object.entries(params).map(([key, value]) => value &&
                   (
                     <Badge key={key} variant="white" className='h-6 mx-2'>
                        {key.charAt(0).toUpperCase() + key.slice(1)} : {value}
                    </Badge>
                ))}
            </div>
        ))}
    </div>
  )
}
