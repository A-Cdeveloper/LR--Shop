import { forwardRef, type ComponentProps, type HTMLInputTypeAttribute } from 'react';

import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type InputCustumProps = ComponentProps<'input'> & {
  label: string;
  showLabel?: boolean;
  error?: string;
  type?: HTMLInputTypeAttribute;
};

const InputCustum = forwardRef<HTMLInputElement, InputCustumProps>(
  (
    {
      label,
      className,
      disabled,
      type = 'text',
      name,
      placeholder,
      required,
      showLabel = false,
      error,
      ...props
    },
    ref,
  ) => (
    <div className="space-y-1">
      {showLabel && (
        <Label aria-disabled={disabled} htmlFor={name}>
          {label}
        </Label>
      )}
      <Input
        id={name}
        ref={ref}
        aria-disabled={disabled}
        aria-invalid={Boolean(error)}
        disabled={disabled}
        type={type}
        name={name}
        placeholder={placeholder}
        required={required}
        className={className}
        {...props}
      />
      {error && <p className="text-xs text-destructive">{error}</p>}
    </div>
  ),
);

InputCustum.displayName = 'InputCustum';

export default InputCustum;
