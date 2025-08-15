const schemaData = [
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    name: "VideosRoom",
    url: "https://videosroom.com",
    potentialAction: {
      "@type": "SearchAction",
      target: "https://videosroom.com/search?q={search_term_string}",
      "query-input": "required name=search_term_string",
    },
  },
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    name: "VideosRoom",
    url: "https://videosroom.com",
    logo: "https://videosroom.com/logo.png",
    sameAs: [
      "https://www.facebook.com/profile.php?id=61563318532430",
      "https://www.tiktok.com/@videosroomofficial",
    ],
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "Indian Movies",
    url: "https://videosroom.com/categories/indian-movies",
    description:
      "Explore a collection of Indian Movies, including Bollywood and regional cinema.",
    mainEntityOfPage: "https://videosroom.com/categories/indian-movies",
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "Hindi Dubbed Movies",
    url: "https://videosroom.com/categories/hindi-dubbed-movies",
    description:
      "Watch a wide selection of Hindi Dubbed Movies from various languages and genres.",
    mainEntityOfPage: "https://videosroom.com/categories/hindi-dubbed-movies",
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "Tamil Movies",
    url: "https://videosroom.com/categories/tamil",
    description:
      "Discover the latest Tamil Movies, from action-packed blockbusters to classic dramas.",
    mainEntityOfPage: "https://videosroom.com/categories/tamil",
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "Punjabi Movies",
    url: "https://videosroom.com/categories/punjabi",
    description:
      "Browse through the best collection of Punjabi Movies for all genres and moods.",
    mainEntityOfPage: "https://videosroom.com/categories/punjabi",
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "English Movies",
    url: "https://videosroom.com/categories/english",
    description:
      "Watch top English Movies, including Hollywood hits, indie films, and much more.",
    mainEntityOfPage: "https://videosroom.com/categories/english",
  },
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    name: "Netflix Movies",
    url: "https://videosroom.com/categories/netflix",
    description:
      "Enjoy a curated collection of Netflix Movies, including trending and popular releases.",
    mainEntityOfPage: "https://videosroom.com/categories/netflix",
  },
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    itemListElement: [
      {
        "@type": "ListItem",
        position: 1,
        name: "Home",
        item: "https://videosroom.com",
      },
      {
        "@type": "ListItem",
        position: 2,
        name: "Indian Movies",
        item: "https://videosroom.com/categories/indian-movies",
      },
    ],
  },
]

const script = document.createElement("script")
script.type = "application/ld+json"
script.innerHTML = JSON.stringify(schemaData)
document.head.appendChild(script)
