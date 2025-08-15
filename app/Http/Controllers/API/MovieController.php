<?php

namespace App\Http\Controllers\API;

use Str;
use DateTime;
use Carbon\Carbon;
use Goutte\Client;
use App\Models\Movie;
use App\Models\Category;
use App\Models\Actor;
use App\Models\Actress;
use App\Models\Quality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class MovieController extends Controller
{
    public function index()
    {
        try {
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year',
                'movies.status',
                'movies.director',
                'movies.uploadBy',
                'images.url as image_url'
            ])
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->leftJoin('images', 'movies.id', '=', 'images.movie_id')
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->where('movies.status', true)
                ->groupBy('movies.id', 'movies.slug', 'movies.title', 'movies.thumbnail', 'movies.duration', 'movies.views', 'movies.year', 'movies.status', 'movies.director', 'movies.uploadBy', 'images.url')
                // ->orderByRaw("STR_TO_DATE(movies.year, '%d-%m-%Y') DESC") 
                ->orderBy('year', 'desc')
                ->paginate(30);

            return response()->json([
                'status' => true,
                'data' => $movies,
                'error' => null,
                'message' => 'All Movies List!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies.'
            ]);
        }
    }

    public function imdb()
    {
        try {
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
            ])
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->leftJoin('images', 'movies.id', '=', 'images.movie_id')
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->where('movies.status', true)
                ->get();

            return response()->json([
                'status' => true,
                'data' => $movies,
                'error' => null,
                'message' => 'All Movies List!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies.'
            ]);
        }
    }

    public function statusUpdate($id)
    {
        $movie = Movie::find($id);
        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }
        $movie->status = !$movie->status;
        $movie->save();
        return response()->json([
            'message' => 'Movie status updated successfully!',
            'data' => $movie
        ], 200);
    }

    public function mostViewedThisWeek()
    {
        try {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            // Define the category titles to exclude
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];

            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                // ->whereBetween('movies.updated_at', [$startOfWeek, $endOfWeek])
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->where('movies.status', 1)
                ->orderBy('movies.views', 'desc')
                ->limit(12)
                ->get();

            if ($movies->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Most Viewed Movies of the Week!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No movie views updated this week.',
                    'message' => 'No movies found for this week.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the most viewed movies of the week.'
            ]);
        }
    }

    public function mostViewedLast24Hours()
    {
        try {
            $last24Hours = Carbon::now()->subHours(24);
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where(function ($query) use ($last24Hours) {
                    $query->where('movies.updated_at', '>=', $last24Hours)
                        ->orWhere('movies.created_at', '>=', $last24Hours);
                })
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->orderBy('movies.views', 'desc')
                ->limit(10)
                ->get();

            if ($movies->isNotEmpty()) {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Most Viewed Movies in the Last 24 Hours!'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No movie views updated in the last 24 hours.',
                    'message' => 'No movies found for the last 24 hours.'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the most viewed movies in the last 24 hours.'
            ]);
        }
    }

    public function allTimeHighViews()
    {
        try {
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->orderBy('movies.views', 'desc')
                ->limit(5)
                ->get();

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No movies found.',
                    'message' => 'No movies available.'
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'All-time highest viewed movies!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch all-time highest viewed movies.'
            ], 200);
        }
    }

    public function latestMovies()
    {
        try {
            $excludedCategoryTitles = ['Cartoon', 'Songs', 'Drama'];
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where(function ($query) use ($excludedCategoryTitles) {
                    $query->whereNull('categories.name')
                        ->orWhereNotIn('categories.name', $excludedCategoryTitles);
                })
                ->orderBy('movies.created_at', 'desc')
                ->limit(5)
                ->get();

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No movies found.',
                    'message' => 'No movies available.'
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Latest uploaded movies!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the latest movies.'
            ], 200);
        }
    }

    public function latestCartoonMovies()
    {
        try {
            $cartoonCategoryTitle = 'Cartoon';
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where('categories.name', $cartoonCategoryTitle)
                ->orderBy('movies.created_at', 'desc')
                ->limit(5)
                ->get();

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No Cartoon movies found.',
                    'message' => 'No Cartoon movies available.'
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Latest uploaded Cartoon movies!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the latest Cartoon movies.'
            ], 200);
        }
    }

    public function latestSongMovies()
    {
        try {
            // Define the category title to include
            $cartoonCategoryTitle = 'Songs';
            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where('categories.name', $cartoonCategoryTitle)
                ->orderBy('movies.created_at', 'desc')
                ->limit(5)
                ->get();

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No Cartoon movies found.',
                    'message' => 'No Cartoon movies available.'
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Latest uploaded Cartoon movies!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the latest Cartoon movies.'
            ], 200);
        }
    }

    public function latestDramaMovies()
    {
        try {
            // Define the category title to include
            $cartoonCategoryTitle = 'Drama';

            $movies = Movie::select([
                'movies.id',
                'movies.slug',
                'movies.title',
                'movies.thumbnail',
                'movies.duration',
                'movies.views',
                'movies.year'
            ])
                ->distinct()
                ->leftJoin('category_movie', 'movies.id', '=', 'category_movie.movie_id')
                ->leftJoin('categories', 'category_movie.category_id', '=', 'categories.id')
                ->where('categories.name', $cartoonCategoryTitle)
                ->orderBy('movies.created_at', 'desc')
                ->limit(5)
                ->get();

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No Cartoon movies found.',
                    'message' => 'No Cartoon movies available.'
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'data' => $movies,
                    'error' => null,
                    'message' => 'Latest uploaded Cartoon movies!'
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => null,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch the latest Cartoon movies.'
            ], 200);
        }
    }

    public function all_movies()
    {
        try {
            $movies = Movie::with('genres', 'categories', 'actors', 'actresses', 'qualities', 'south_actor', 'tags', 'seasons', 'images')->orderBy('created_at', 'desc')->get();
            return response()->json([
                'status' => true,
                'data' => $movies,
                'error' => null,
                'message' => 'All Movies List!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies.'
            ]);
        }
    }

    public function getFeatured()
    {
        try {
            $movies = Movie::with('categories', 'seasons', 'images')
                ->where('status', 1)
                ->where('isFeatured', 1)
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json([
                'status' => true,
                'data' => $movies,
                'error' => null,
                'message' => 'All Movies List!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies.'
            ]);
        }
    }

    public function formatYear($inputDate)
    {
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Auto-correct partial or misformatted month names
        foreach ($months as $month) {
            // Check if a partial or lowercase version of the month is present in the input date
            if (stripos($inputDate, substr($month, 0, 3)) !== false) {
                // Replace it with the properly capitalized full month name
                $inputDate = preg_replace('/' . substr($month, 0, 3) . '\w*/i', $month, $inputDate);
                break;
            }
        }

        if (preg_match('/^\d{4}$/', $inputDate)) {
            $currentDate = date('d-m'); // Get current day and month
            $inputDate = $currentDate . '-' . $inputDate; // Format it as dd-mm-YYYY
        }

        if (preg_match('/(\w+ )(\d{1})(,? \d{4})/', $inputDate, $matches)) {
            $inputDate = $matches[1] . str_pad($matches[2], 2, '0', STR_PAD_LEFT) . $matches[3];
        }

        $inputDate = preg_replace('/(\d{4})-(\d{1})-(\d{1})/', '$1-0$2-0$3', $inputDate);
        $inputDate = preg_replace('/(\d{4})-(\d{2})-(\d{1})/', '$1-$2-0$3', $inputDate);
        $inputDate = preg_replace('/(\d{4})-(\d{1})-(\d{2})/', '$1-0$2-$3', $inputDate);

        $formats = [
            'Ymd',      // yyyyMMdd
            'Y-m-d',    // yyyy-MM-dd
            'dmY',      // ddMMyy
            'd-m-Y',    // dd-MM-yyyy
            'd/m/y',    // d/M/yy
            'd/m/Y',    // d/M/yyyy
            'F d, Y',   // August 30, 2013
            'M d, Y',   // May 3, 2024
            'd F Y',    // 27 September 2024
            'F d Y',    // September 27 2024
        ];
        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $inputDate);
            if ($dateTime && $dateTime->format($format) === $inputDate) {
                return $dateTime->format('d-m-Y');
            }
        }
        return null;
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 200);
            }

            $slug = $request->has('title') ? \Str::slug($request->input('title')) : null;

            if (Movie::where('slug', $slug)->exists()) {
                $movie = Movie::where('slug', $slug)->first();
                return response()->json([
                    'status' => false,
                    'error' => 'Movie already exists. Please choose a different title.',
                    'message' => 'Movie already exists.',
                    'movie' => $movie
                ], 200);
            }

            $formattedYear = $this->formatYear($request->input('year'));

            $movieData = [
                'slug' => $slug,
                'title' => $request->input('title') . ' watch online free',
                'description' => $request->input('description'),
                'meta_description' => $request->input('meta_description'),
                'duration' => $request->input('duration'),
                'status' => false,
                'views' => $request->input('views', 0),
                'year' => $formattedYear,
                'director' => $request->input('director'),
                'uploadBy' => $request->input('uploadBy'),
            ];

            // Add download links, iframe links, and iframeMobile fields
            for ($i = 1; $i <= 10; $i++) {
                $movieData["download_link$i"] = $request->input("download_link$i");
                $movieData["iframe_link$i"] = $request->input("iframe_link$i");
                $movieData["iframePosition$i"] = $request->input("iframePosition$i");
            }

            if ($request->hasFile('thumbnail')) {
                Log::info('Thumbnail upload detected.');

                try {
                    // Step 1: Get the uploaded file
                    $thumbnail = $request->file('thumbnail');

                    // Step 2: Define the path for saving the image
                    $path = public_path('thumbnail/');

                    // Step 3: Generate a unique name for the image
                    $thumbnail_name = $request->input('title') . '.webp';

                    // Step 4: Create an instance of the image
                    $image = Image::make($thumbnail);

                    // Step 5: Resize the image (keeping aspect ratio)
                    $image->resize(null, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    // Step 6: Add watermark
                    $watermark = Image::make('https://videosroom.com/public/images/logo.png');
                    $watermark->resize(100, 50, function ($constraint) {
                        $constraint->aspectRatio(); // Maintain aspect ratio
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    $watermark->opacity(70);
                    $image->insert($watermark, 'top-left', 10, 10);

                    $image->encode('webp', 75);

                    // Step 8: Save the processed image to the path
                    $image->save($path . $thumbnail_name);

                    // Step 9: Assign the thumbnail name to the movie data
                    $movieData['thumbnail'] = $thumbnail_name;
                } catch (\Exception $e) {
                    Log::error('Error processing thumbnail: ' . $e->getMessage());
                }
            }
            $url = $request->input('url');

            if ($request->filled('url') && !empty($request->input('url'))) {
                try {
                    // Step 1: Get the image contents from the URL
                    $url = $request->input('url');
                    $imageContents = @file_get_contents($url);

                    if ($imageContents === false) {
                        return response()->json([
                            'status' => false,
                            'error' => "Failed to download image from the provided URL",
                            'message' => 'Failed to add movie due to an unexpected error.'
                        ], 200);
                        // return response()->json(['error' => 'Failed to download image from the provided URL'], 500);
                    }

                    // Step 2: Define the path for saving the image
                    $path = public_path('thumbnail/');

                    // Step 3: Ensure the directory exists
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }

                    // Step 4: Generate a unique name for the image
                    $thumbnailName = $request->input('title') . '.webp';

                    // Step 5: Create an instance of the image from the downloaded contents
                    $image = Image::make($imageContents);

                    // Step 6: Resize the image (keeping aspect ratio)
                    $image->resize(null, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    // Step 7: Add watermark
                    $watermark = Image::make('https://videosroom.com/public/images/logo.png');
                    $watermark->resize(100, 50, function ($constraint) {
                        $constraint->aspectRatio(); // Maintain aspect ratio
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    $watermark->opacity(70);
                    $image->insert($watermark, 'top-left', 10, 10);

                    // Step 8: Encode the image as WebP format
                    $image->encode('webp', 75);

                    // Step 9: Save the processed image to the path
                    $image->save($path . $thumbnailName);

                    // Step 10: Assign the thumbnail name to the movie data
                    $movieData['thumbnail'] = $thumbnailName;
                } catch (\Exception $e) {
                    Log::error('Error processing image from URL: ' . $e->getMessage());
                    return response()->json(['error' => 'Error processing the image'], 500);
                }
            }


            $movie = Movie::create($movieData);

            // Handle categories
            if ($request->has('category_ids') && !in_array(0, $request->input('category_ids', []))) {
                $movie->categories()->sync($request->input('category_ids', []));
            }

            if ($request->has('quality_ids') && !in_array(0, $request->input('quality_ids', []))) {
                $movie->qualities()->sync($request->input('quality_ids', []));
            }

            // Handle actors
            if ($request->has('actors_ids') && !in_array(0, $request->input('actors_ids', []))) {
                $movie->actors()->sync($request->input('actors_ids', []));
            }

            // Handle actresses
            if ($request->has('actresses_ids') && !in_array(0, $request->input('actresses_ids', []))) {
                $movie->actresses()->sync($request->input('actresses_ids', []));
            }

            // Handle south actors
            if ($request->has('south_actor_ids') && !in_array(0, $request->input('south_actor_ids', []))) {
                $movie->south_actor()->sync($request->input('south_actor_ids', []));
            }

            // Handle tags
            if ($request->has('tags_ids') && !in_array(0, $request->input('tags_ids', []))) {
                $movie->tags()->sync($request->input('tags_ids', []));
            }

            // Handle seasons
            if ($request->has('seasons_ids') && !in_array(0, $request->input('seasons_ids', []))) {
                $movie->seasons()->sync($request->input('seasons_ids', []));
            }

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $image) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move('images/', $imageName);
                    $movie->images()->create(['url' => $imageName]);
                }
            }

            return response()->json([
                'status' => true,
                'data' => $movie,
                'error' => "",
                'message' => "Movie Added Successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add movie due to an unexpected error.'
            ], 200);
        }
    }

    public function storeA(Request $request)
    {
        try {
            $slug = $request->has('title') ? \Str::slug($request->input('title')) : null;
            $formattedYear = $this->formatYear($request->input('year'));
            $movieData = [
                'slug' => $slug,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'meta_description' => $request->input('meta_description'),
                'duration' => $request->input('duration'),
                'status' => false,
                'views' => $request->input('views', 0),
                'year' => $formattedYear,
                'director' => $request->input('director'),
                'uploadBy' => $request->input('uploadBy'),
            ];



            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $path = 'thumbnail/';
                $thumbnail_name = uniqid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($path, $thumbnail_name);
                $movieData['thumbnail'] = $thumbnail_name;
            }

            $movie = Movie::create($movieData);

            return response()->json([
                'status' => true,
                'data' => $movie,
                'error' => "",
                'message' => "Movie Added Successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add movie due to an unexpected error.'
            ], 200);
        }
    }

    private function convertToEmbedUrl($url)
    {
        if (strpos($url, 'youtube.com/watch?v=') !== false) {
            // Extract the video ID from the URL
            preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|(?:.*[?&]v=)))?([^"&?\/\s]{11})/i', $url, $matches);

            if (isset($matches[1])) {
                // Return the embedded URL
                return 'https://www.youtube.com/embed/' . $matches[1];
            }
        }
        return $url;
    }

    public function addMovieCSV(Request $request)
    {
        try {
            // Check if the file is provided
            if (!$request->hasFile('file')) {
                return response()->json([
                    'status' => false,
                    'error' => 'No file uploaded.',
                    'message' => 'Please upload a CSV file.'
                ], 400);
            }

            // Retrieve the uploaded file
            $file = $request->file('file');

            // Read the contents of the file and remove the BOM if it exists
            $csvData = file_get_contents($file->getRealPath());

            // Remove BOM (if any) from the file content
            $csvData = preg_replace('/\xEF\xBB\xBF/', '', $csvData);

            // Create a temporary file to store cleaned data
            $cleanedFile = tmpfile();
            fwrite($cleanedFile, $csvData);
            fseek($cleanedFile, 0);

            // Read the content of the cleaned file resource using stream_get_contents
            $csvContent = stream_get_contents($cleanedFile);

            // Parse the CSV content
            $csv = array_map('str_getcsv', explode("\n", $csvContent));

            // Get the header row
            $header = array_shift($csv);

            // Loop through the rows and insert data
            foreach ($csv as $row) {
                // Ensure the row has the same number of elements as the header
                if (count($row) == count($header)) {
                    $rowData = array_combine($header, $row);

                    // Ensure that the "Link", "Title", "Year", and "Image" keys are present in the row
                    if (isset($rowData['Link']) && isset($rowData['Title'])) {
                        $slug = \Str::slug($rowData['Title']);
                        $youtubeUrl = $rowData['Link'];

                        $embedUrl = $this->convertToEmbedUrl($youtubeUrl);


                        // Check if the movie already exists
                        if (Movie::where('slug', $slug)->exists()) {
                            continue; // Skip this movie if it already exists
                        }

                        $formattedYear = $this->formatYear($rowData['Year']);


                        $movieData = [
                            'slug' => $slug,
                            'title' => $rowData['Title'],
                            'description' => $rowData['Link'], // Assuming 'Video Link' is the description for now
                            'status' => false,
                            'views' => 0,
                            'year' => $formattedYear,
                            'download_link1' => $rowData['Link'],
                            'iframe_link1' => $embedUrl, // If you need iframe data, you can add them here
                            'iframePosition1' => "1", // Adjust as per the structure of your CSV
                        ];


                        $url = $rowData['Image'];
                        // Check if the URL is a Base64 string
                        if (preg_match('/^data:image\/(\w+);base64,/', $url, $type)) {
                            // Get the base64 string without the prefix
                            $base64Data = substr($url, strpos($url, ',') + 1);
                            $imageContents = base64_decode($base64Data);

                            if ($imageContents === false) {
                                return response()->json(['error' => 'Failed to decode Base64 image data'], 500);
                            }

                            // Define the path to save the thumbnail
                            $path = 'thumbnail/';
                            $thumbnailName = uniqid() . '.jpg'; // Generate a unique file name

                            // Save the image to the public directory
                            file_put_contents(public_path($path . $thumbnailName), $imageContents);
                        } else {
                            // Handle regular URL case (as before)
                            $imageContents = @file_get_contents($url);
                            if ($imageContents === false) {
                                return response()->json(['error' => 'Failed to download image from the provided URL'], 500);
                            }

                            // Define the path to save the thumbnail
                            $path = 'thumbnail/';
                            $thumbnailName = uniqid() . '.jpg';

                            // Save the image to the public directory
                            file_put_contents(public_path($path . $thumbnailName), $imageContents);
                        }


                        $movieData['thumbnail'] = $thumbnailName;
                        Movie::create($movieData);
                    }
                } else {
                    \Log::error("Row does not match header: " . json_encode($row));
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Movies added successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to add movies due to an unexpected error.'
            ], 200);
        }
    }

    public function show($id)
    {
        try {
            $movie = Movie::with('categories', 'actors', 'actresses', 'qualities', 'south_actor', 'tags', 'seasons', 'images')->findOrFail($id);
        } catch (\Exception $e) {
            try {
                $movie = Movie::with('categories', 'actors', 'actresses', 'qualities', 'south_actor', 'tags', 'seasons', 'images')->where('slug', $id)->firstOrFail();
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'Movie not found.',
                    'message' => 'Failed to fetch movie.'
                ], 200);
            }
        }
        return response()->json([
            'status' => true,
            'data' => $movie,
            'error' => null,
            'message' => 'Movie fetched successfully!'
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $slug = $request->has('title') ? \Str::slug($request->input('title')) : $movie->slug;
            if (Movie::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                return response()->json([
                    'status' => false,
                    'error' => 'Movie with this title already exists. Please choose a different title.',
                    'message' => 'Movie with this title already exists'
                ], 200);
            }
            $formattedYear = $this->formatYear($request->input('year'));
            $movieData = [
                'slug' => $slug,
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'meta_description' => $request->input('meta_description'),
                'duration' => $request->input('duration'),
                'year' => $formattedYear,
                'duration' => $request->input('duration'),
                'status' => $movie->status,
                'director' => $request->input('director'),
                'isFeatured' => $request->has('isFeatured') ? (int)$request->input('isFeatured') : 0,
            ];

            // Add download links
            for ($i = 1; $i <= 10; $i++) {
                if ($request->has("download_link$i")) {
                    $movieData["download_link$i"] = $request->input("download_link$i");
                }
            }

            // Add Iframe Link links
            for ($i = 1; $i <= 10; $i++) {
                if ($request->has("iframe_link$i")) {
                    $movieData["iframe_link$i"] = $request->input("iframe_link$i");
                }
            }

            // Add Iframe Position
            for ($i = 1; $i <= 10; $i++) {
                if ($request->has("iframePosition$i")) {
                    $movieData["iframePosition$i"] = $request->input("iframePosition$i");
                }
            }

            if ($request->hasFile('thumbnail')) {
                // Remove the old thumbnail if exists
                if ($movie->thumbnail && file_exists(public_path('thumbnail/' . $movie->thumbnail))) {
                    unlink(public_path('thumbnail/' . $movie->thumbnail));
                }

                $thumbnail = $request->file('thumbnail');
                $path = 'thumbnail/';
                $thumbnail_name = uniqid() . '.' . $thumbnail->getClientOriginalExtension();
                $thumbnail->move($path, $thumbnail_name);
                $movieData['thumbnail'] = $thumbnail_name;
            }

            $movie->update($movieData);
            $imagePath = public_path('images/');

            // Handle images
            if ($request->hasFile('images')) {
                // Remove old images
                foreach ($movie->images as $image) {
                    if (file_exists(public_path('images/' . $image->url))) {
                        unlink(public_path('images/' . $image->url));
                    }
                    $image->delete();
                }

                // Add new images
                $images = $request->file('images');
                foreach ($images as $image) {
                    $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($imagePath, $imageName);
                    // $image->move('images/', $imageName);
                    $movie->images()->create(['url' => $imageName]);
                }
            }

            // Sync categories
            if ($request->has('category_ids') && !in_array(0, $request->input('category_ids', []))) {
                $movie->categories()->sync($request->input('category_ids', []));
            }

            if ($request->has('genre_ids') && !in_array(0, $request->input('genre_ids', []))) {
                $movie->genres()->sync($request->input('genre_ids', []));
            }

            if ($request->has('quality_ids') && !in_array(0, $request->input('quality_ids', []))) {
                $movie->qualities()->sync($request->input('quality_ids', []));
            }

            // Handle actors
            if ($request->has('actors_ids') && !in_array(0, $request->input('actors_ids', []))) {
                $movie->actors()->sync($request->input('actors_ids', []));
            }

            // Handle actresses
            if ($request->has('actresses_ids') && !in_array(0, $request->input('actresses_ids', []))) {
                $movie->actresses()->sync($request->input('actresses_ids', []));
            }

            // Handle south actors
            if ($request->has('south_actor_ids') && !in_array(0, $request->input('south_actor_ids', []))) {
                $movie->south_actor()->sync($request->input('south_actor_ids', []));
            }

            // Handle tags
            if ($request->has('tags_ids') && !in_array(0, $request->input('tags_ids', []))) {
                $movie->tags()->sync($request->input('tags_ids', []));
            }

            // Handle seasons
            if ($request->has('seasons_ids') && !in_array(0, $request->input('seasons_ids', []))) {
                $movie->seasons()->sync($request->input('seasons_ids', []));
            }

            return response()->json([
                'status' => true,
                'data' => $movie,
                'error' => "",
                'message' => "Movie Updated Successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update movie due to an unexpected error.'
            ], 500);
        }
    }


    public function updateImdb(Request $request, $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movieData = [
                'description' => $request->input('description'),
                'meta_description' => $request->input('meta_description'),
                'duration' => $request->input('duration'),
            ];

            $movie->update($movieData);
            return response()->json([
                'status' => true,
                'data' => $movie,
                'error' => "",
                'message' => "Movie Updated Successfully!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update movie due to an unexpected error.'
            ], 500);
        }
    }



    public function bulkUpdate(Request $request)
    {
        try {
            $moviesData = $request->input('movies');
            $source = $request->input('source');

            $movieSource = null;
            $download_link_vidguard = null;

            if (!is_array($moviesData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid data format. Expected an array of movies.'
                ], 200);
            }

            $iframeWithListeamed = null;
            $downloadLinkWithListeamed = null;
            $hasListeamedLink = false;

            foreach ($moviesData as $movieData) {
                $slug = isset($movieData['title']) ? \Str::slug($movieData['title']) : null;
                $movie = Movie::where('slug', $slug)->first();

                if (!$movie) {
                    continue;
                }

                if ($source === 1) {
                    $movieSource = "listeamed";
                    $download_link_vidguard = str_replace('/e/', '/v/', $movieData['url']);
                } else if ($source === 2) {
                    $movieSource = "iplayerhls";
                    $download_link_vidguard = str_replace('/e/', '/f/', $movieData['url']);
                } else if ($source === 3) {
                    $movieSource = "dhtpre";
                    $download_link_vidguard = str_replace('/embed/', '/download/', $movieData['url']);
                } else {
                    $movieSource = "short.ink";
                    $download_link_vidguard = $movieData['url'];
                }


                foreach ($movie->getAttributes() as $key => $value) {
                    if (str_starts_with($key, 'iframe_link') && !empty($value) && str_contains($value, $movieSource)) {
                        $iframeWithListeamed = $key;
                        break;
                    }
                }

                foreach ($movie->getAttributes() as $key => $value) {
                    if (str_starts_with($key, 'download_link') && !empty($value) && str_contains($value, $movieSource)) {
                        $downloadLinkWithListeamed = $key;
                        break;
                    }
                }

                for ($i = 1; $i <= 10; $i++) {
                    $downloadLinkField = "download_link" . $i;
                    if (!empty($movie->$downloadLinkField) && str_contains($movie->$downloadLinkField, $movieSource)) {
                        $hasListeamedLink = true;
                        break;
                    }
                }

                $movie->slug = $slug;
                $movie->title = $movieData['title'] ?? $movie->title;
                $movie->description = $movieData['title'] ?? $movie->description;
                $movie->meta_description = $movieData['title'] ?? $movie->meta_description;

                if ($hasListeamedLink) {
                    if ($iframeWithListeamed) {
                        $movie->{$iframeWithListeamed} = $movieData['url'];
                    }
                    if ($downloadLinkWithListeamed) {
                        $movie->{$downloadLinkWithListeamed} = $download_link_vidguard;
                    }
                } else {
                    for ($i = 1; $i <= 10; $i++) {
                        $iframeField = "iframe_link" . $i;
                        if (empty($movie->$iframeField)) {
                            $movie->$iframeField = $movieData['url'] ?? "Contact Waqar";
                            break;
                        }
                    }

                    for ($i = 1; $i <= 10; $i++) {
                        $downloadLinkField = "download_link" . $i;
                        if (empty($movie->$downloadLinkField)) {
                            $movie->$downloadLinkField = $download_link_vidguard ?? "Contact Waqar for Download";
                            break;
                        }
                    }
                }

                $movie->save();
            }

            return response()->json([
                'status' => true,
                'iframe' => $hasListeamedLink,
                'download' => $downloadLinkWithListeamed,
                'message' => 'Movies updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to update movies due to an unexpected error.'
            ], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();
            return response()->json([
                'status' => true,
                'error' => null,
                'message' => 'Movie Deleted successfully!'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Movie not found',
                'message' => null
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'An error occurred while deleting the movie',
                'message' => null
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Movie::query();
            $query->where('status', 1);


            if ($request->has('title')) {
                $query->where('title', 'like', '%' . $request->title . '%');
            }

            if ($request->has('year')) {
                $query->where('year', $request->year);
            }

            // Add other search criteria as needed
            if ($request->has('category_id')) {
                $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('category_id', $request->category_id);
                });
            }

            // Handle seasons filter
            if ($request->has('season_id')) {
                $query->whereHas('seasons', function ($q) use ($request) {
                    $q->where('season_id', $request->season_id);
                });
            }

            $movies = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => true,
                'data' => $movies,
                'error' => null,
                'message' => 'Search Results'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'data' => [],
                'error' => $e->getMessage(),
                'message' => 'Failed to perform search.'
            ]);
        }
    }

    public function updateThumbnails()
    {
        $movies = Movie::all();
        foreach ($movies as $movie) {
            try {
                $oldThumbnail = $movie->thumbnail;
                $extension = 'webp'; // We're converting to .webp
                $newThumbnail = $movie->slug . '.' . $extension;

                // Define the old and new file paths in the public/thumbnail directory
                $oldFilePath = public_path('thumbnail/' . $oldThumbnail);
                $newFilePath = public_path('thumbnail/' . $newThumbnail);

                // Check if the old file exists
                if (File::exists($oldFilePath)) {
                    // Check for unsupported formats (AVIF)
                    $imageType = mime_content_type($oldFilePath);

                    if ($imageType === 'image/avif') {
                        // Convert AVIF to JPG (or PNG) before processing
                        $image = Image::make($oldFilePath)->encode('jpg');
                    } else {
                        // Load the image normally
                        $image = Image::make($oldFilePath);
                    }

                    // Resize the image to a height of 300px while maintaining aspect ratio
                    $image->resize(null, 300, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    // Add watermark to the image
                    $watermark = Image::make('https://videosroom.com/public/images/logo.png'); // You can also load local watermark
                    $watermark->resize(100, 50, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize(); // Prevent upsizing smaller images
                    });

                    $watermark->opacity(70);
                    $image->insert($watermark, 'top-left', 10, 10); // Position watermark at top-left corner

                    // Encode the image as .webp and save it
                    $image->encode('webp', 75); // 75 is the quality of the webp image

                    // Save the image to the new file path
                    $image->save($newFilePath);

                    // Update the database with the new filename (with .webp extension)
                    $movie->update(['thumbnail' => $newThumbnail]);
                    File::delete($oldFilePath);
                }
            } catch (\Exception $e) {
                \Log::error('Error processing movie ID ' . $movie->id . ': ' . $e->getMessage());
                continue;
            }
        }

        return response()->json(['message' => 'Thumbnails updated and saved as .webp successfully.']);
    }

    public function suggestions($title)
    {
        $normalizedTitle = strtolower(str_replace(' ', '', $title));
        $suggestions = Movie::where('status', 1)
            ->whereRaw("REPLACE(LOWER(title), ' ', '') LIKE ?", ["%{$normalizedTitle}%"])
            ->limit(7)
            ->get(['id', 'slug', 'title', 'thumbnail']);

        return response()->json($suggestions);
    }

    public function saveThumbnailFromUrl(Request $request)
    {
        $request->validate([
            'movieId' => 'required|integer',
            'url' => 'required|string',
        ]);

        $movieId = $request->input('movieId');
        $movie = Movie::find($movieId);

        if (!$movie) {
            return response()->json(['error' => 'Movie not found'], 404);
        }

        $movieSlug = $movie->slug;
        $url = $request->input('url');


        if ($request->input('imageType') == "thumbnail") {
            $path = public_path('thumbnail/');

            $thumbnailName = $movieSlug . '.webp';

            try {
                // Step 1: Get the image contents from the URL
                $imageContents = @file_get_contents($url);
                if ($imageContents === false) {
                    return response()->json([
                        'status' => false,
                        'data' => null,
                        'error' => 'Failed to download image from the provided URL.',
                        'message' => 'Failed to download image from the provided URL'
                    ], 200);
                }

                // Step 2: Ensure the directory exists
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                // Step 3: Create an instance of the image from the downloaded contents
                $image = Image::make($imageContents);

                // Step 4: Resize the image (keeping aspect ratio)
                $image->resize(null, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing smaller images
                });

                // Step 5: Add watermark
                $watermark = Image::make('https://videosroom.com/public/images/logo.png');
                $watermark->resize(100, 50, function ($constraint) {
                    $constraint->aspectRatio(); // Maintain aspect ratio
                    $constraint->upsize(); // Prevent upsizing smaller images
                });

                $watermark->opacity(70);
                $image->insert($watermark, 'top-left', 10, 10);

                // Step 6: Encode the image as WebP format
                $image->encode('webp', 75);

                // Step 7: Save the processed image to the path
                $image->save($path . $thumbnailName);

                // Step 8: Update the movie thumbnail
                $movie->thumbnail = $thumbnailName;
                $movie->save();

                return response()->json(['success' => 'Thumbnail saved successfully', 'path' => $path . $thumbnailName]);
            } catch (\Exception $e) {
                Log::error('Error processing thumbnail: ' . $e->getMessage());
                return response()->json(['error' => 'Error processing thumbnail: ' . $e->getMessage()], 500);
            }
        } else {
            $path = public_path('images/');
            $BannerImageName = $movieSlug . '.webp';

            try {
                foreach ($movie->images as $image) {
                    if (file_exists($path . $image->url)) {
                        Log::info('Deleting image file: ' . $path . $image->url);
                        unlink($path . $image->url);
                    }
                    Log::info('Deleting database record for image: ' . $image->url);
                    $image->delete();
                }

                if (isset($url) && !empty($url)) {
                    $imageContents = @file_get_contents($url);

                    if ($imageContents === false) {
                        Log::error('Failed to fetch image from URL: ' . $url);
                        return response()->json(['error' => 'Failed to fetch image from the provided URL'], 400);
                    }

                    // Process the image
                    $image = Image::make($imageContents);

                    // Add watermark
                    $watermark = Image::make('https://videosroom.com/public/images/logo.png');
                    $watermark->resize(100, 50, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $watermark->opacity(70);
                    $image->insert($watermark, 'top-left', 10, 10);

                    // Save the processed image
                    $image->encode('webp', 75);
                    $image->save($path . $BannerImageName);

                    // Create a new database record for the image
                    $movie->images()->create(['url' => $BannerImageName]);
                } else {
                    Log::warning('No URL provided for movie ID: ' . $movie->id);
                }

                return response()->json(['success' => 'Images updated successfully']);
            } catch (\Exception $e) {
                // Log error details
                Log::error('Error updating images for movie ID: ' . $movie->id . ' - ' . $e->getMessage());
                return response()->json(['error' => 'Error updating images: ' . $e->getMessage()], 500);
            }
        }
    }

    public function saveThumbnail($videoId, $movieId)
    {
        $thumbnailUrl = "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
        $imageContents = file_get_contents($thumbnailUrl);
        if ($imageContents === false) {
            return response()->json(['error' => 'Failed to download thumbnail'], 500);
        }
        $path = 'thumbnail/';
        $thumbnailName = uniqid() . '.jpg';
        file_put_contents(public_path($path . $thumbnailName), $imageContents);
        $movie = Movie::find($movieId);
        if ($movie) {
            $movie->thumbnail = $thumbnailName;
            $movie->save();
            return response()->json(['success' => 'Thumbnail saved successfully', 'path' => $path . $thumbnailName]);
        }
        return response()->json(['error' => 'Movie not found'], 404);
    }

    public function getMoviesByYear($year)
    {
        try {
            $movies = Movie::where('year', 'LIKE', '%' . $year)
                ->select(['id', 'slug', 'title', 'thumbnail', 'duration', 'views', 'year'])
                ->paginate(30);

            if ($movies->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'error' => 'No movies found for the specified year.',
                    'message' => 'No movies available for the given year.'
                ], 200);
            }

            return response()->json([
                'status' => true,
                'data' => $movies,
                'message' => 'Movies fetched successfully!'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
                'message' => 'Failed to fetch movies due to an unexpected error.'
            ], 500);
        }
    }

    public function getAllMovieByFilter($year) {}
}
