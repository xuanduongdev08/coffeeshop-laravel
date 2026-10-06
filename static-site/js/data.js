/**
 * Dữ liệu tĩnh nguồn mở (JSON / JS) cho XDTHECOFFEEHOUSE
 * Không cần bất kỳ backend hay server database nào!
 */

const CATEGORIES = [
  { id: 1, name: "Cà phê truyền thống & Ý", slug: "ca-phe", icon: "☕", desc: "Hương vị cà phê phin đậm đà và các dòng Espresso thượng hạng" },
  { id: 2, name: "Hạt cà phê nguyên chất", slug: "hat-ca-phe", icon: "🌱", desc: "Hạt rang mộc từ nông trường Cầu Đất & Buôn Ma Thuột" },
  { id: 3, name: "Trà & Nước trái cây", slug: "nuoc-trai-cay", icon: "🍹", desc: "Thanh mát, tươi mới với trái cây nhiệt đới thượng hạng" },
  { id: 4, name: "Bánh ngọt & Tráng miệng", slug: "banh-ngot", icon: "🍰", desc: "Bánh nướng tươi mỗi ngày, kết hợp hoàn hảo cùng cà phê" }
];

const PRODUCTS = [
  {
    id: 1,
    category_id: 1,
    name: "Cà phê Capuccino",
    slug: "ca-phe-capuccino",
    price: 59000,
    image: "images/menu-1.jpg",
    badge: "Bán chạy nhất",
    desc: "Sự kết hợp hoàn hảo giữa Espresso đậm vị, sữa nóng và lớp bọt sữa bồng bềnh mịn màng như nhung.",
    has_size: true,
    sizes: [
      { size: "M", price: 49000 },
      { size: "L", price: 59000 },
      { size: "XL", price: 69000 }
    ],
    rating: 4.9,
    reviews_count: 128
  },
  {
    id: 2,
    category_id: 1,
    name: "Cà phê Sữa Đá Sài Gòn",
    slug: "ca-phe-sua-da-sai-gon",
    price: 39000,
    image: "images/menu-2.jpg",
    badge: "Di sản Việt",
    desc: "Hạt Robusta Đắk Lắk pha phin truyền thống, hòa quyện sữa đặc béo ngậy và đá mát lạnh.",
    has_size: true,
    sizes: [
      { size: "M", price: 35000 },
      { size: "L", price: 39000 },
      { size: "XL", price: 45000 }
    ],
    rating: 5.0,
    reviews_count: 245
  },
  {
    id: 3,
    category_id: 1,
    name: "Cà phê Đen Phin Mộc",
    slug: "ca-phe-den-phin-moc",
    price: 35000,
    image: "images/menu-3.jpg",
    badge: "Đậm vị",
    desc: "Hương vị nguyên bản đậm đà của cà phê rang mộc không tẩm ướp, hậu vị đắng thanh quyến rũ.",
    has_size: true,
    sizes: [
      { size: "M", price: 30000 },
      { size: "L", price: 35000 },
      { size: "XL", price: 40000 }
    ],
    rating: 4.8,
    reviews_count: 96
  },
  {
    id: 4,
    category_id: 1,
    name: "Caramel Latte Nghệ Thuật",
    slug: "caramel-latte-nghe-thuat",
    price: 65000,
    image: "images/menu-5.jpg",
    badge: "Mới",
    desc: "Espresso nồng nàn cùng sữa tươi đánh nóng, điểm xuyết sốt caramel ngọt dịu thủ công.",
    has_size: true,
    sizes: [
      { size: "M", price: 55000 },
      { size: "L", price: 65000 },
      { size: "XL", price: 75000 }
    ],
    rating: 4.9,
    reviews_count: 74
  },
  {
    id: 5,
    category_id: 1,
    name: "Cold Brew Ủ Lạnh 24h",
    slug: "cold-brew-u-lanh-24h",
    price: 55000,
    image: "images/menu-6.jpg",
    badge: "Đặc biệt",
    desc: "Hạt Arabica Cầu Đất ủ lạnh suốt 24 giờ, mang hương thơm hoa quả tự nhiên, thanh tao và ít axit.",
    has_size: true,
    sizes: [
      { size: "M", price: 50000 },
      { size: "L", price: 55000 },
      { size: "XL", price: 65000 }
    ],
    rating: 4.9,
    reviews_count: 112
  },
  {
    id: 6,
    category_id: 1,
    name: "Cà phê Muối Cố Đô",
    slug: "ca-phe-muoi-co-do",
    price: 45000,
    image: "images/menu-7.jpg",
    badge: "Hot Trend",
    desc: "Sự thăng hoa độc đáo giữa vị cà phê phin đậm đà và lớp kem muối béo mặn bồng bềnh đặc trưng xứ Huế.",
    has_size: true,
    sizes: [
      { size: "M", price: 39000 },
      { size: "L", price: 45000 },
      { size: "XL", price: 52000 }
    ],
    rating: 5.0,
    reviews_count: 189
  },
  {
    id: 7,
    category_id: 2,
    name: "Hạt Arabica Cầu Đất (Gói 250g)",
    slug: "hat-arabica-cau-dat-250g",
    price: 135000,
    image: "images/menu-8.jpg",
    badge: "Thượng hạng",
    desc: "Thu hoạch ở độ cao 1.600m tại Đà Lạt. Hương hoa quả phong phú, vị chua thanh thoát và ngọt hậu.",
    has_size: false,
    rating: 4.9,
    reviews_count: 83
  },
  {
    id: 8,
    category_id: 2,
    name: "Hạt Robusta Honey Buôn Ma Thuột (250g)",
    slug: "hat-robusta-honey-bmt-250g",
    price: 110000,
    image: "images/menu-9.jpg",
    badge: "Rang mộc",
    desc: "Chế biến theo phương pháp mật ong (Honey process), giữ trọn độ ngọt tự nhiên và thể chất dày dặn.",
    has_size: false,
    rating: 4.8,
    reviews_count: 67
  },
  {
    id: 9,
    category_id: 2,
    name: "Blend Di Sản Signature (500g)",
    slug: "blend-di-san-signature-500g",
    price: 210000,
    image: "images/menu-10.jpg",
    badge: "Công thức riêng",
    desc: "Tỉ lệ vàng 70% Robusta & 30% Arabica tạo nên tách cà phê hài hòa, thơm nồng quyến rũ.",
    has_size: false,
    rating: 5.0,
    reviews_count: 142
  },
  {
    id: 10,
    category_id: 3,
    name: "Trà Đào Cam Sả Hoàng Gia",
    slug: "tra-dao-cam-sa-hoang-gia",
    price: 49000,
    image: "images/drink-1.jpg",
    badge: "Thanh mát",
    desc: "Trà đen ủ lạnh hương cam thanh khiết, những lát đào giòn ngọt cùng hương sả thoang thoảng dịu nhẹ.",
    has_size: true,
    sizes: [
      { size: "M", price: 42000 },
      { size: "L", price: 49000 },
      { size: "XL", price: 55000 }
    ],
    rating: 4.8,
    reviews_count: 98
  },
  {
    id: 11,
    category_id: 3,
    name: "Nước Ép Lựu Đỏ Nhiệt Đới",
    slug: "nuoc-ep-luu-do-nhiet-doi",
    price: 52000,
    image: "images/drink-4.jpg",
    badge: "Healthy",
    desc: "100% nguyên chất ép tươi không thêm đường hóa học, dồi dào vitamin và chất chống oxy hóa.",
    has_size: true,
    sizes: [
      { size: "M", price: 45000 },
      { size: "L", price: 52000 },
      { size: "XL", price: 59000 }
    ],
    rating: 4.7,
    reviews_count: 62
  },
  {
    id: 12,
    category_id: 3,
    name: "Trà Vải Hoa Hồng Lãng Mạn",
    slug: "tra-vai-hoa-hong-lang-man",
    price: 49000,
    image: "images/drink-6.jpg",
    badge: "Yêu thích",
    desc: "Vị ngọt thanh dịu của quả vải chín mọng quyện cùng hương cánh hoa hồng thơm ngát tao nhã.",
    has_size: true,
    sizes: [
      { size: "M", price: 42000 },
      { size: "L", price: 49000 },
      { size: "XL", price: 55000 }
    ],
    rating: 4.9,
    reviews_count: 105
  },
  {
    id: 13,
    category_id: 4,
    name: "Bánh Tiramisu Truyền Thống",
    slug: "banh-tiramisu-truyen-thong",
    price: 45000,
    image: "images/dessert-1.jpg",
    badge: "Hảo hạng",
    desc: "Lớp phô mai Mascarpone mềm mịn, cốt bánh Savoiardi ngấm đẫm cà phê Espresso thơm lừng.",
    has_size: false,
    rating: 5.0,
    reviews_count: 156
  },
  {
    id: 14,
    category_id: 4,
    name: "Croissant Bơ Pháp Thượng Hạng",
    slug: "croissant-bo-phap-thuong-hang",
    price: 35000,
    image: "images/dessert-2.jpg",
    badge: "Nướng tươi",
    desc: "Bánh sừng bò ngàn lớp thơm lừng bơ Pháp, giòn xốp bên ngoài và mềm ẩm ngọt ngào bên trong.",
    has_size: false,
    rating: 4.9,
    reviews_count: 88
  },
  {
    id: 15,
    category_id: 4,
    name: "Bánh Phô Mai Việt Quất (Blueberry Cheesecake)",
    slug: "blueberry-cheesecake",
    price: 52000,
    image: "images/dessert-4.jpg",
    badge: "Best Seller",
    desc: "Phô mai nướng béo ngậy phủ mứt việt quất chua ngọt cân bằng, đế bánh quy bơ giòn rụm.",
    has_size: false,
    rating: 4.9,
    reviews_count: 119
  },
  {
    id: 16,
    category_id: 4,
    name: "Mousse Cacao Đậm Đà",
    slug: "mousse-cacao-dam-da",
    price: 48000,
    image: "images/dessert-5.jpg",
    badge: "Ngọt ngào",
    desc: "Hạt ca cao nguyên chất Đắk Lắk kết hợp kem tươi sánh mịn, vị đắng nhẹ và thơm béo kéo dài.",
    has_size: false,
    rating: 4.8,
    reviews_count: 73
  }
];

const BLOGS = [
  {
    id: 1,
    title: "Nghệ thuật thưởng thức Cà phê Phin — Nhịp thở chậm của người Việt",
    slug: "nghe-thuat-ca-phe-phin",
    date: "15 Tháng 10, 2026",
    category: "Văn hóa Cà phê",
    image: "images/image_1.jpg",
    excerpt: "Ngồi nhìn từng giọt cà phê chầm chậm rơi không chỉ là cách chiết xuất hương vị, mà là một nốt lặng tâm hồn giữa cuộc sống hiện đại tất bật."
  },
  {
    id: 2,
    title: "Hành trình từ Cầu Đất đến tách cà phê: Bí quyết của những hạt mộc",
    slug: "hanh-trinh-cau-dat",
    date: "28 Tháng 09, 2026",
    category: "Nguồn gốc hạt",
    image: "images/image_2.jpg",
    excerpt: "Độ cao 1.600m cùng sương mù mát lạnh quanh năm đã tôi luyện nên những hạt Arabica Cầu Đất danh tiếng khắp thế giới như thế nào?"
  },
  {
    id: 3,
    title: "Phân biệt Arabica và Robusta: Chọn gu cà phê chuẩn vị cho riêng bạn",
    slug: "phan-biet-arabica-robusta",
    date: "10 Tháng 09, 2026",
    category: "Kiến thức Barista",
    image: "images/image_3.jpg",
    excerpt: "Bạn là người yêu thích vị đắng đậm đà tràn đầy năng lượng của Robusta hay vị chua thanh nhã, thơm nồng nàn của Arabica?"
  }
];

const GALLERY_ITEMS = [
  { title: "Góc ban công ngập nắng", category: "outdoor", image: "images/gallery-1.jpg" },
  { title: "Quầy Barista pha chế thủ công", category: "indoor", image: "images/gallery-2.jpg" },
  { title: "Không gian đọc sách ấm cúng", category: "indoor", image: "images/gallery-3.jpg" },
  { title: "Sân vườn xanh mát bóng cây", category: "outdoor", image: "images/gallery-4.jpg" },
  { title: "Tách Latte buổi sớm mai", category: "coffee", image: "images/image_4.jpg" },
  { title: "Hương vị bánh nướng mới ra lò", category: "dessert", image: "images/dessert-3.jpg" }
];

const TESTIMONIALS = [
  {
    name: "Thạc sĩ Nguyễn Minh Tuấn",
    role: "Giảng viên Kiến trúc",
    avatar: "images/person_2.jpg",
    text: "Không gian ở XDTHECOFFEEHOUSE mang một chất thơ rất riêng. Từng góc ngồi đều được chăm chút, cà phê pha phin ở đây có hậu vị ngọt thanh chuẩn hạt mộc mà tôi tìm kiếm bấy lâu."
  },
  {
    name: "Trần Mai Phương",
    role: "Food & Travel Blogger",
    avatar: "images/person_3.jpg",
    text: "Món Cà phê Muối và Bánh Tiramisu ở đây là sự kết hợp điểm 10/10! Nhân viên phục vụ nhã nhặn, âm nhạc êm dịu, rất thích hợp để làm việc hoặc trò chuyện cùng bạn bè."
  },
  {
    name: "Lê Hoàng Quân",
    role: "Chuyên viên Sáng tạo Nội dung",
    avatar: "images/person_4.jpg",
    text: "Mình hay ngồi góc ban công ngắm phố. Cold Brew ủ lạnh 24h ở đây thực sự đỉnh cao, uống vào cảm nhận rõ hương hoa quả tự nhiên chứ không hề gắt cổ."
  }
];

window.COFFEE_DATA = {
  categories: CATEGORIES,
  products: PRODUCTS,
  blogs: BLOGS,
  gallery: GALLERY_ITEMS,
  testimonials: TESTIMONIALS
};
