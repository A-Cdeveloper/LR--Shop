import { api } from '@/lib/api';
import { setToken } from '@/lib/token';
import type { LoginCredentials } from '../schemas/loginSchema';
import type { AuthUser, LoginSuccessResponse } from '../types/auth';

const wait = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

export async function login(credentials: LoginCredentials): Promise<AuthUser> {
  await wait(4000);
  const response = await api.post<LoginSuccessResponse>('/login', credentials);

  const user = response.data.data;

  setToken(user.token);

  return user;
}
