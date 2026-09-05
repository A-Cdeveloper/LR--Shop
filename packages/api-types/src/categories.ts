export type Category = {
  id: number;
  name: string;
  slug: string;
  description: string | null;
  image: string | null;
  products_count: number;
};

export type CategoriesResponse = {
  data: Category[];
};

export type CategoryResponse = {
  data: Category;
};
