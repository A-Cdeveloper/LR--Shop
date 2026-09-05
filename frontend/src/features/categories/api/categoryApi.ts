import { api } from '@/lib/api';
import type { CategoriesResponse, Category, CategoryResponse } from '@shop/api-types';

export const getCategories = async (): Promise<CategoriesResponse> => {
  const response = await api.get<CategoriesResponse>('/categories');
  return response.data;
};

export const getCategoryBySlug = async (slug: string): Promise<Category> => {
  if (!slug) {
    throw new Error('Slug is required');
  }

  const response = await api.get<CategoryResponse>(`/categories/${slug}`);

  return response.data.data;
};
