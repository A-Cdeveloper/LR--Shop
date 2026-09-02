import { z } from 'zod';

import { emailSchema } from './commonSchema';

export const forgotPassSchema = z.object({
  email: emailSchema,
});

export type ForgotPassCredentials = z.infer<typeof forgotPassSchema>;
