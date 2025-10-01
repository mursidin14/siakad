import { cn } from '@/lib/utils';
import { IconCaretDown, IconCheck } from '@tabler/icons-react';
import { useState } from 'react';
import { Button } from './ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from './ui/command';
import { Popover, PopoverContent, PopoverTrigger } from './ui/popover';

export default function ComboBox({ items, selectedItem, onSelect, placeholder = 'Pilih item...' }) {
    const [isOpen, setIsOpen] = useState(false);

    const handleSelect = (value) => {
        onSelect(value);
        setIsOpen(false);
    };

    return (
        <Popover open={isOpen} onOpenChange={setIsOpen}>
            <PopoverTrigger asChild>
                <Button
                    variant="outline"
                    role="combobox"
                    aria-expanded={isOpen}
                    className="w-full justify-between"
                    size="xl"
                >
                    {items.find((item) => item.label === selectedItem)?.label ?? placeholder}
                    <IconCaretDown className="ml-2 size-5 shrink-0 opacity-50" />
                </Button>
            </PopoverTrigger>
            <PopoverContent
                className="max-h-[--radix-popover-content-avaiable-height] w-[--radix-popover-content-avaiable-width] p-0"
                align="start"
            >
                <Command>
                    <CommandInput placeholder={placeholder} className="h-9" />
                    <CommandList>
                        <CommandEmpty>Item tidak ditemukan</CommandEmpty>
                        <CommandGroup>
                            {items.map((item, index) => (
                                <CommandItem key={index} value={item.value} onSelect={(value) => handleSelect(value)}>
                                    {item.label}
                                    <IconCheck
                                        className={cn(
                                            'ml-auto size-4',
                                            selectedItem === item.label ? 'opacity-100' : 'opacity-0',
                                        )}
                                    />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
