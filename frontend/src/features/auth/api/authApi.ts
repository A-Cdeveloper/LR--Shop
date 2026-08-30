import { api } from '@/lib/api';
import { setToken } from '@/lib/token';
import type { AuthUser, LoginCredentials, LoginSuccessResponse } from '../types/auth';

const wait = (ms: number) => new Promise((resolve) => setTimeout(resolve, ms));

export async function login(credentials: LoginCredentials): Promise<AuthUser> {
  await wait(4000);
  const response = await api.post<LoginSuccessResponse>('/login', credentials);

  const user = response.data.data;

  setToken(user.token);

  return user;
}
