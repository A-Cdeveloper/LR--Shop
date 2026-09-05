import { Spinner } from '@shop/ui';
import { cn } from '@/lib/utils';

const Loading = ({ className, title }: { className?: string; title?: string }) => {
  return (
    <div className="flex flex-col items-center justify-center h-full gap-2">
      <Spinner className={cn('w-10 h-10 animate-spin', className)} />
      {title && <p className="text-sm text-muted-foreground">{title}</p>}
    </div>
  );
};

export default Loading;
