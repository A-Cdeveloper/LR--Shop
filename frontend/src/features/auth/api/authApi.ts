import { api } from '@/lib/api';
import { setToken } from '@/lib/token';
import type { LoginCredentials } from '../schemas/loginSchema';
import type { AuthUser, LoginSuccessResponse, RegisterSuccessResponse } from '../types/auth';
import type { RegisterCredentials } from '../schemas/registerSchema';
import type { ResetPassCredentials } from '../schemas/resetPassSchema';
import type { ForgotPassCredentials } from '../schemas/forgotPassSchema';

/**
 * Login the user
 * @param credentials - The login credentials
 * @returns The authenticated user
 */
export async function login(credentials: LoginCredentials): Promise<AuthUser> {
  const response = await api.post<LoginSuccessResponse>('/login', credentials);

  const user = response.data.data;

  setToken(user.token);

  return user;
}

/**
 * Register a new user
 * @param credentials - The registration credentials
 * @returns The registered user
 */
export async function register(credentials: RegisterCredentials): Promise<RegisterSuccessResponse> {
  const response = await api.post<RegisterSuccessResponse>('/register', credentials);

  return response.data;
}

/**
 * Forgot the password
 * @param email - The email address to forgot the password for
 * @returns The forgot password response
 */
export async function forgotPassword(
  credentials: ForgotPassCredentials,
): Promise<{ message: string }> {
  if (!credentials.email) {
    throw new Error('Email address is missing.');
  }

  const response = await api.post('/forgot-password', { email: credentials.email });
  return response.data;
}

export async function resetPassword(
  credentials: ResetPassCredentials,
): Promise<{ message: string }> {
  if (!credentials.token) {
    throw new Error('Token is missing.');
  }
  const response = await api.post('/reset-password', credentials);
  return response.data;
}

/**
 * Resend the verification email
 * @param email - The email address to resend the verification email to
 * @returns The verification email
 */
export async function resendVerificationEmail(email: string): Promise<void> {
  if (!email) {
    throw new Error('Email address is missing.');
  }

  await api.post('/email/verification-notification', { email });
}
