import axios from 'axios';
export type ApiMessageError = {
  message: string;
  code?: string;
};

export type ApiValidationError = {
  message: string;
  errors: Record<string, string[]>;
  code?: string;
};

export type ApiErrorResponse = ApiMessageError | ApiValidationError;

export function getApiError(error: unknown): ApiErrorResponse | null {
  if (!axios.isAxiosError<ApiErrorResponse>(error)) {
    return null;
  }

  return error.response?.data ?? null;
}

export function getApiErrorMessages(error: ApiErrorResponse | null): string[] {
  if (!error || !('errors' in error)) {
    return error ? [error.message] : [];
  }

  return Object.values(error.errors).flat();
}
