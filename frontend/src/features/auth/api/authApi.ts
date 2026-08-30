import { api } from '@/lib/api';
import { setToken } from '@/lib/token';
import type { LoginCredentials } from '../schemas/loginSchema';
import type { AuthUser, LoginSuccessResponse, RegisterSuccessResponse } from '../types/auth';
import type { RegisterCredentials } from '../schemas/registerSchema';

const wait = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

export async function login(credentials: LoginCredentials): Promise<AuthUser> {
  await wait(4000);
  const response = await api.post<LoginSuccessResponse>('/login', credentials);

  const user = response.data.data;

  setToken(user.token);

  return user;
}

export async function register(credentials: RegisterCredentials): Promise<RegisterSuccessResponse> {
  await wait(4000);
  const response = await api.post<RegisterSuccessResponse>('/register', credentials);

  return response.data;
}

export async function resendVerificationEmail(email: string): Promise<void> {
  if (!email) {
    throw new Error('Email address is missing.');
  }

  await api.post('/email/verification-notification', { email });
}
