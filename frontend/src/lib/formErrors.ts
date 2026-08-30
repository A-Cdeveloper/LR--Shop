import type { ZodError } from 'zod';

export function getZodFieldErrors<T extends Record<string, unknown>>(
  error: ZodError<T>,
): Partial<Record<keyof T, string>> {
  const fieldErrors: Partial<Record<keyof T, string>> = {};

  for (const issue of error.issues) {
    const field = issue.path[0] as keyof T;
    if (!fieldErrors[field]) {
      fieldErrors[field] = issue.message;
    }
  }

  return fieldErrors;
}
