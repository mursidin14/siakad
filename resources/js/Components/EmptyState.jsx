import React from 'react'

export default function EmptyState({
    title = 'Tidak ada data',
    subtitle = 'Mulai dengan menambahkan data baru',
    icon: Icon,
}) {
  return (
    <div className='flex flex-col items-center p-4 border border-dashed border-secondary'>
        <Icon className="size-12 text-blue-600" />
        <h3 className='mt-2 text-lg font-semibold text-foreground'>{title}</h3>
        <p className='mt-1 text-sm text-muted-foreground'>{subtitle}</p>
    </div>
  )
}
