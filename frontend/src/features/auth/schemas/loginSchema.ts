import { z } from 'zod';

import { emailSchema, basicPasswordSchema } from './commonSchema';

export const loginSchema = z.object({
  email: emailSchema,
  password: basicPasswordSchema,
});

export type LoginCredentials = z.infer<typeof loginSchema>;
