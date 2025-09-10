import ApplicationLogo from '@/Components/ApplicationLogo'
import NavigationMenu from '@/Components/NavigationMenu'
import { Avatar, AvatarFallback } from '@/Components/ui/avatar'
import { Button } from '@/Components/ui/button'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu'
import { cn } from '@/lib/utils'
import { Disclosure } from '@headlessui/react'
import { Link } from '@inertiajs/react'
import { IconChevronCompactDown, IconLayoutSidebar, IconLogout2, IconX } from '@tabler/icons-react'
import React from 'react'

export default function HeaderStudentLayout({url}) {
  return (
        <>
            <Disclosure
                as="nav"
                className="py-4 border-b border-blue-300 border-opacity-25 bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 lg:border-none"
            >

                {({ open }) => (
                    <>
                        <div className='px-6 lg:px-24'>
                            <div className='relative flex items-center justify-between h-16'>
                                <div className='flex items-center'>
                                    <ApplicationLogo
                                        bgLogo='from-orange-500 via-orange-600 to-orange-600'
                                        colorText='text-white'
                                        colorLogo='text-white'
                                    />
                                </div>

                                <div className='flex lg:hidden'>
                                    {/* Mobile menu button */}

                                    <Disclosure.Button 
                                        className="relative inline-flex items-center justify-center rounded-xl p-2 text-white hover:text-white focus:outline-none"
                                    >
                                        <span className='absolute -inset-0.5' />
                                       
                                       {open ? (
                                            <IconX className='block size-6' />
                                       ) : (
                                           <IconLayoutSidebar className='block size-6' />
                                       )}

                                    </Disclosure.Button>


                                </div>

                                <div className='hidden lg:block lg:ml-4'>
                                    <div className='flex items-center'>
                                       <div className='hidden lg:max-10 lg:block'>
                                            <div className='flex space-x-4'>
                                                <NavigationMenu 
                                                    url='#'
                                                    active={url.startsWith('/student/dashboard')}
                                                    title='Dashboard'
                                                />
                                                <NavigationMenu 
                                                    url='#'
                                                    active={url.startsWith('/student/schedule')}
                                                    title='Jadwal'
                                                />
                                                <NavigationMenu 
                                                    url='#'
                                                    active={url.startsWith('/student/study-plan')}
                                                    title='Kartu Rencana Studi'
                                                />
                                                <NavigationMenu 
                                                    url='#'
                                                    active={url.startsWith('/student/study-result')}
                                                    title='Kartu Hasil Studi'
                                                />
                                                <NavigationMenu 
                                                    url='#'
                                                    active={url.startsWith('/student/fee')}
                                                    title='Pembayaran'
                                                />
                                                
                                            </div>
                                       </div>

                                       {/* profile dropdown */}
                                       <DropdownMenu>
                                            <DropdownMenuTrigger asChild>
                                                <Button
                                                    variant="blue"
                                                    size="xl"
                                                    className="data-[state=open]:bg-orange-500 data-[state=open]:text-white"
                                                >
                                                    <Avatar className='size-8 rounded-lg'>
                                                        <AvatarFallback className="text-blue-600 rounded-lg">X</AvatarFallback>
                                                    </Avatar>
                                                    <div className='grid flex-1 text-sm leading-tight text-left'>
                                                        <span className='font-semibold truncate'>John Doe</span>
                                                        <span className='text-xs truncate'>jhondoe@gmail.com</span>
                                                    </div>

                                                    <IconChevronCompactDown className='ml-auto size-4' />

                                                </Button>
                                            </DropdownMenuTrigger>

                                            <DropdownMenuContent
                                                className='w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg'
                                                side="bottom"
                                                align="end"
                                                sideOffset={4}
                                            >
                                                <DropdownMenuLabel className='p-0 font-normal'>
                                                    <div className='flex items-center gap-2 px-1 py-1.5 text-left text-sm'>
                                                        <Avatar className='size-8 rounded-lg'>
                                                            <AvatarFallback className="text-blue-600 rounded-lg">X</AvatarFallback>
                                                        </Avatar>
                                                        <div className='grid flex-1 leading-tight text-left text-sm'>
                                                            <span className='font-semibold truncate'>John Doe</span>
                                                            <span className='text-xs truncate'>jhondoe@gmail.com</span>
                                                        </div>
                                                    </div>
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuItem asChild>
                                                    <Link href='#'>
                                                        <IconLogout2 />
                                                        Logout
                                                    </Link>
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>

                                       </DropdownMenu>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {/* Mobile menu */}
                        <Disclosure.Panel className="lg:hidden">
                            <div className="space-y-1 px-2 pt-2 pb-3">
                                <Disclosure.Button 
                                  as="a"
                                  href="#"
                                  className={cn(
                                    url.startsWith('/student/dashboard') ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                                    'block rounded-md px-3 py-2 text-base font-medium'
                                  )}
                                >
                                  Dashboard
                                </Disclosure.Button>

                                <Disclosure.Button 
                                  as="a"
                                  href="#"
                                  className={cn(
                                    url.startsWith('/student/schedule') ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                                    'block rounded-md px-3 py-2 text-base font-medium'
                                  )}
                                >
                                  Jadwal
                                </Disclosure.Button>

                                <Disclosure.Button 
                                  as="a"
                                  href="#"
                                  className={cn(
                                    url.startsWith('/student/study-plan') ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                                    'block rounded-md px-3 py-2 text-base font-medium'
                                  )}
                                >
                                  Kartu Rencana Studi
                                </Disclosure.Button>

                                <Disclosure.Button 
                                  as="a"
                                  href="#"
                                  className={cn(
                                    url.startsWith('/student/study-result') ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                                    'block rounded-md px-3 py-2 text-base font-medium'
                                  )}
                                >
                                  Kartu Hasil Studi
                                </Disclosure.Button>

                                <Disclosure.Button 
                                  as="a"
                                  href="#"
                                  className={cn(
                                    url.startsWith('/student/fee') ? 'bg-blue-500 text-white' : 'text-white hover:bg-blue-500',
                                    'block rounded-md px-3 py-2 text-base font-medium'
                                 )}
                                >
                                  Pembayaran
                                </Disclosure.Button>

                            </div>

                            <div className='pt-4 pb-3'>
                                <div className='flex items-center px-5'>
                                    <div className='flex-shrink-0'>
                                        <Avatar>
                                            <AvatarFallback>X</AvatarFallback>
                                        </Avatar>
                                    </div>

                                    <div className='ml-3'>
                                        <div className='text-base font-medium text-white'>John Doe</div>
                                        <div className='text-sm font-medium text-white'>jhondoe@gmail.com</div>
                                    </div>

                                </div>

                                 <div className='px-2 mt-3 space-y-1'>
                                    <Disclosure.Button
                                        as="a"
                                        href="#"
                                        className={'block rounded-md px-3 py-2 text-base font-medium text-white hover:bg-blue-500'}
                                    >
                                        Logout
                                    </Disclosure.Button>
                                 </div>

                            </div>

                        </Disclosure.Panel>
                    </>
                )}
            </Disclosure>

            <header className='py-6'></header>

        </>
  )
}
