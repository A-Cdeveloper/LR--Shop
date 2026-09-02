type FormWrapperProps = {
  children: React.ReactNode;
  title?: string;
  description?: string;
};

const FormWrapper = ({ children, title, description }: FormWrapperProps) => {
  return (
    <div className="flex w-full max-w-[370px] flex-col gap-4 rounded-md border border-default bg-muted-foreground/5 p-8 shadow-xs">
      {title && <h1 className="text-2xl font-bold">{title}</h1>}
      {description && <p className="text-sm text-muted-foreground">{description}</p>}
      {children}
    </div>
  );
};

export default FormWrapper;
