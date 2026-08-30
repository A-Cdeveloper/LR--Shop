export type AuthUser = {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
  role: string;
  phone: string | null;
  shipping_address: string | null;
  city: string | null;
  state: string | null;
  zip: string | null;
  country: string | null;
  token?: string;
};

export type LoginSuccessResponse = {
  data: AuthUser & {
    token: string;
  };
};
