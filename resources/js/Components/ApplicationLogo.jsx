import { cn } from "@/lib/utils";
import { Link } from "@inertiajs/react";
import { IconSchool } from "@tabler/icons-react";

export default function ApplicationLogo(bgLogo, colorLogo, colorText) {
    return (
        <Link 
            href="#"
            className={cn("flex flex-row items-center gap-x-2")}
        >
            <div className={cn("text-foreground flex aspect-square size-12 items-center justify-center rounded-full bg-gradient-to-r", bgLogo)}>
                <IconSchool className={cn("size-8", colorLogo)} />
            </div>
            <div className={cn("grid flex-1 text-left leading-tight", colorText)}>
                <span className="font-bold truncate">SIAKAD</span>
                <span className="truncate text-xs tracking-tighter">Teman Setia Mahasiswa</span>
            </div>

        </Link>
    );
}
