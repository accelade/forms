<?php

declare(strict_types=1);

namespace Accelade\Forms\Data;

/**
 * Predefined emoji data organized by category.
 */
final class EmojiData
{
    /**
     * @return array<string, string>
     */
    public static function smileys(): array
    {
        return [
            '😀' => 'grinning face',
            '😃' => 'grinning face with big eyes',
            '😄' => 'grinning face with smiling eyes',
            '😁' => 'beaming face with smiling eyes',
            '😆' => 'grinning squinting face',
            '😅' => 'grinning face with sweat',
            '🤣' => 'rolling on the floor laughing',
            '😂' => 'face with tears of joy',
            '🙂' => 'slightly smiling face',
            '🙃' => 'upside-down face',
            '😉' => 'winking face',
            '😊' => 'smiling face with smiling eyes',
            '😇' => 'smiling face with halo',
            '🥰' => 'smiling face with hearts',
            '😍' => 'smiling face with heart-eyes',
            '🤩' => 'star-struck',
            '😘' => 'face blowing a kiss',
            '😗' => 'kissing face',
            '😚' => 'kissing face with closed eyes',
            '😙' => 'kissing face with smiling eyes',
            '🥲' => 'smiling face with tear',
            '😋' => 'face savoring food',
            '😛' => 'face with tongue',
            '😜' => 'winking face with tongue',
            '🤪' => 'zany face',
            '😝' => 'squinting face with tongue',
            '🤑' => 'money-mouth face',
            '🤗' => 'hugging face',
            '🤭' => 'face with hand over mouth',
            '🤫' => 'shushing face',
            '🤔' => 'thinking face',
            '🤐' => 'zipper-mouth face',
            '🤨' => 'face with raised eyebrow',
            '😐' => 'neutral face',
            '😑' => 'expressionless face',
            '😶' => 'face without mouth',
            '😏' => 'smirking face',
            '😒' => 'unamused face',
            '🙄' => 'face with rolling eyes',
            '😬' => 'grimacing face',
            '🤥' => 'lying face',
            '😌' => 'relieved face',
            '😔' => 'pensive face',
            '😪' => 'sleepy face',
            '🤤' => 'drooling face',
            '😴' => 'sleeping face',
            '😷' => 'face with medical mask',
            '🤒' => 'face with thermometer',
            '🤕' => 'face with head-bandage',
            '🤢' => 'nauseated face',
            '🤮' => 'face vomiting',
            '🤧' => 'sneezing face',
            '🥵' => 'hot face',
            '🥶' => 'cold face',
            '🥴' => 'woozy face',
            '😵' => 'dizzy face',
            '🤯' => 'exploding head',
            '🤠' => 'cowboy hat face',
            '🥳' => 'partying face',
            '🥸' => 'disguised face',
            '😎' => 'smiling face with sunglasses',
            '🤓' => 'nerd face',
            '🧐' => 'face with monocle',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function people(): array
    {
        return [
            '👋' => 'waving hand',
            '🤚' => 'raised back of hand',
            '🖐️' => 'hand with fingers splayed',
            '✋' => 'raised hand',
            '🖖' => 'vulcan salute',
            '👌' => 'ok hand',
            '🤌' => 'pinched fingers',
            '🤏' => 'pinching hand',
            '✌️' => 'victory hand',
            '🤞' => 'crossed fingers',
            '🤟' => 'love-you gesture',
            '🤘' => 'sign of the horns',
            '🤙' => 'call me hand',
            '👈' => 'backhand index pointing left',
            '👉' => 'backhand index pointing right',
            '👆' => 'backhand index pointing up',
            '🖕' => 'middle finger',
            '👇' => 'backhand index pointing down',
            '☝️' => 'index pointing up',
            '👍' => 'thumbs up',
            '👎' => 'thumbs down',
            '✊' => 'raised fist',
            '👊' => 'oncoming fist',
            '🤛' => 'left-facing fist',
            '🤜' => 'right-facing fist',
            '👏' => 'clapping hands',
            '🙌' => 'raising hands',
            '👐' => 'open hands',
            '🤲' => 'palms up together',
            '🤝' => 'handshake',
            '🙏' => 'folded hands',
            '✍️' => 'writing hand',
            '💪' => 'flexed biceps',
            '🦾' => 'mechanical arm',
            '🦿' => 'mechanical leg',
            '🦵' => 'leg',
            '🦶' => 'foot',
            '👂' => 'ear',
            '🦻' => 'ear with hearing aid',
            '👃' => 'nose',
            '🧠' => 'brain',
            '👀' => 'eyes',
            '👁️' => 'eye',
            '👅' => 'tongue',
            '👄' => 'mouth',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function animals(): array
    {
        return [
            '🐶' => 'dog face',
            '🐱' => 'cat face',
            '🐭' => 'mouse face',
            '🐹' => 'hamster',
            '🐰' => 'rabbit face',
            '🦊' => 'fox',
            '🐻' => 'bear',
            '🐼' => 'panda',
            '🐨' => 'koala',
            '🐯' => 'tiger face',
            '🦁' => 'lion',
            '🐮' => 'cow face',
            '🐷' => 'pig face',
            '🐸' => 'frog',
            '🐵' => 'monkey face',
            '🙈' => 'see-no-evil monkey',
            '🙉' => 'hear-no-evil monkey',
            '🙊' => 'speak-no-evil monkey',
            '🐒' => 'monkey',
            '🦍' => 'gorilla',
            '🦧' => 'orangutan',
            '🐔' => 'chicken',
            '🐧' => 'penguin',
            '🐦' => 'bird',
            '🐤' => 'baby chick',
            '🦆' => 'duck',
            '🦅' => 'eagle',
            '🦉' => 'owl',
            '🦇' => 'bat',
            '🐺' => 'wolf',
            '🐗' => 'boar',
            '🐴' => 'horse face',
            '🦄' => 'unicorn',
            '🐝' => 'honeybee',
            '🐛' => 'bug',
            '🦋' => 'butterfly',
            '🐌' => 'snail',
            '🐞' => 'lady beetle',
            '🐜' => 'ant',
            '🦟' => 'mosquito',
            '🦂' => 'scorpion',
            '🐢' => 'turtle',
            '🐍' => 'snake',
            '🦎' => 'lizard',
            '🐙' => 'octopus',
            '🦑' => 'squid',
            '🦐' => 'shrimp',
            '🦞' => 'lobster',
            '🦀' => 'crab',
            '🐡' => 'blowfish',
            '🐠' => 'tropical fish',
            '🐟' => 'fish',
            '🐬' => 'dolphin',
            '🐳' => 'whale',
            '🐋' => 'humpback whale',
            '🦈' => 'shark',
            '🐊' => 'crocodile',
            '🐅' => 'tiger',
            '🐆' => 'leopard',
            '🦓' => 'zebra',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function food(): array
    {
        return array_merge(
            self::foodFruits(),
            self::foodVegetables(),
            self::foodMeals(),
            self::foodDesserts()
        );
    }

    /**
     * @return array<string, string>
     */
    private static function foodFruits(): array
    {
        return [
            '🍎' => 'red apple',
            '🍐' => 'pear',
            '🍊' => 'tangerine',
            '🍋' => 'lemon',
            '🍌' => 'banana',
            '🍉' => 'watermelon',
            '🍇' => 'grapes',
            '🍓' => 'strawberry',
            '🫐' => 'blueberries',
            '🍈' => 'melon',
            '🍒' => 'cherries',
            '🍑' => 'peach',
            '🥭' => 'mango',
            '🍍' => 'pineapple',
            '🥥' => 'coconut',
            '🥝' => 'kiwi fruit',
            '🍅' => 'tomato',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function foodVegetables(): array
    {
        return [
            '🥑' => 'avocado',
            '🥦' => 'broccoli',
            '🥬' => 'leafy green',
            '🥒' => 'cucumber',
            '🌶️' => 'hot pepper',
            '🫑' => 'bell pepper',
            '🌽' => 'ear of corn',
            '🥕' => 'carrot',
            '🧄' => 'garlic',
            '🧅' => 'onion',
            '🥔' => 'potato',
            '🍠' => 'roasted sweet potato',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function foodMeals(): array
    {
        return [
            '🥐' => 'croissant',
            '🥯' => 'bagel',
            '🍞' => 'bread',
            '🥖' => 'baguette bread',
            '🥨' => 'pretzel',
            '🧀' => 'cheese wedge',
            '🥚' => 'egg',
            '🍳' => 'cooking',
            '🧈' => 'butter',
            '🥞' => 'pancakes',
            '🧇' => 'waffle',
            '🥓' => 'bacon',
            '🥩' => 'cut of meat',
            '🍗' => 'poultry leg',
            '🍖' => 'meat on bone',
            '🌭' => 'hot dog',
            '🍔' => 'hamburger',
            '🍟' => 'french fries',
            '🍕' => 'pizza',
            '🥪' => 'sandwich',
            '🥙' => 'stuffed flatbread',
            '🧆' => 'falafel',
            '🌮' => 'taco',
            '🌯' => 'burrito',
            '🥗' => 'green salad',
            '🥘' => 'shallow pan of food',
            '🫕' => 'fondue',
            '🍝' => 'spaghetti',
            '🍜' => 'steaming bowl',
            '🍲' => 'pot of food',
            '🍛' => 'curry rice',
            '🍣' => 'sushi',
            '🍱' => 'bento box',
            '🥟' => 'dumpling',
            '🍤' => 'fried shrimp',
            '🍙' => 'rice ball',
            '🍚' => 'cooked rice',
            '🍘' => 'rice cracker',
            '🍥' => 'fish cake',
            '🥠' => 'fortune cookie',
            '🍢' => 'oden',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function foodDesserts(): array
    {
        return [
            '🍧' => 'shaved ice',
            '🍨' => 'ice cream',
            '🍦' => 'soft ice cream',
            '🥧' => 'pie',
            '🧁' => 'cupcake',
            '🍰' => 'shortcake',
            '🎂' => 'birthday cake',
            '🍮' => 'custard',
            '🍭' => 'lollipop',
            '🍬' => 'candy',
            '🍫' => 'chocolate bar',
            '🍿' => 'popcorn',
            '🍩' => 'doughnut',
            '🍪' => 'cookie',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function travel(): array
    {
        return [
            '🚗' => 'automobile',
            '🚕' => 'taxi',
            '🚙' => 'sport utility vehicle',
            '🚌' => 'bus',
            '🚎' => 'trolleybus',
            '🏎️' => 'racing car',
            '🚓' => 'police car',
            '🚑' => 'ambulance',
            '🚒' => 'fire engine',
            '🚐' => 'minibus',
            '🛻' => 'pickup truck',
            '🚚' => 'delivery truck',
            '🚛' => 'articulated lorry',
            '🚜' => 'tractor',
            '🏍️' => 'motorcycle',
            '🛵' => 'motor scooter',
            '🚲' => 'bicycle',
            '🛴' => 'kick scooter',
            '✈️' => 'airplane',
            '🛫' => 'airplane departure',
            '🛬' => 'airplane arrival',
            '🚀' => 'rocket',
            '🛸' => 'flying saucer',
            '🚁' => 'helicopter',
            '🛶' => 'canoe',
            '⛵' => 'sailboat',
            '🚤' => 'speedboat',
            '🛥️' => 'motor boat',
            '🛳️' => 'passenger ship',
            '⛴️' => 'ferry',
            '🚢' => 'ship',
            '🚂' => 'locomotive',
            '🚃' => 'railway car',
            '🚄' => 'high-speed train',
            '🚅' => 'bullet train',
            '🚆' => 'train',
            '🚇' => 'metro',
            '🚈' => 'light rail',
            '🚉' => 'station',
            '🏠' => 'house',
            '🏡' => 'house with garden',
            '🏢' => 'office building',
            '🏣' => 'Japanese post office',
            '🏥' => 'hospital',
            '🏦' => 'bank',
            '🏨' => 'hotel',
            '🏩' => 'love hotel',
            '🏪' => 'convenience store',
            '🏫' => 'school',
            '🏬' => 'department store',
            '🏭' => 'factory',
            '🏯' => 'Japanese castle',
            '🏰' => 'castle',
            '💒' => 'wedding',
            '🗼' => 'Tokyo tower',
            '🗽' => 'Statue of Liberty',
            '⛪' => 'church',
            '🕌' => 'mosque',
            '🛕' => 'hindu temple',
            '🕍' => 'synagogue',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function activities(): array
    {
        return [
            '⚽' => 'soccer ball',
            '🏀' => 'basketball',
            '🏈' => 'american football',
            '⚾' => 'baseball',
            '🥎' => 'softball',
            '🎾' => 'tennis',
            '🏐' => 'volleyball',
            '🏉' => 'rugby football',
            '🥏' => 'flying disc',
            '🎱' => 'pool 8 ball',
            '🪀' => 'yo-yo',
            '🏓' => 'ping pong',
            '🏸' => 'badminton',
            '🏒' => 'ice hockey',
            '🏑' => 'field hockey',
            '🥍' => 'lacrosse',
            '🏏' => 'cricket game',
            '🪃' => 'boomerang',
            '🥅' => 'goal net',
            '⛳' => 'flag in hole',
            '🪁' => 'kite',
            '🏹' => 'bow and arrow',
            '🎣' => 'fishing pole',
            '🤿' => 'diving mask',
            '🥊' => 'boxing glove',
            '🥋' => 'martial arts uniform',
            '🎽' => 'running shirt',
            '🛹' => 'skateboard',
            '🛼' => 'roller skate',
            '🛷' => 'sled',
            '⛸️' => 'ice skate',
            '🥌' => 'curling stone',
            '🎿' => 'skis',
            '⛷️' => 'skier',
            '🏂' => 'snowboarder',
            '🪂' => 'parachute',
            '🏋️' => 'person lifting weights',
            '🤸' => 'person cartwheeling',
            '🤼' => 'people wrestling',
            '🤽' => 'person playing water polo',
            '🤾' => 'person playing handball',
            '🏌️' => 'person golfing',
            '🏇' => 'horse racing',
            '⛹️' => 'person bouncing ball',
            '🧗' => 'person climbing',
            '🚴' => 'person biking',
            '🚵' => 'person mountain biking',
            '🎪' => 'circus tent',
            '🎭' => 'performing arts',
            '🎨' => 'artist palette',
            '🎬' => 'clapper board',
            '🎤' => 'microphone',
            '🎧' => 'headphone',
            '🎼' => 'musical score',
            '🎹' => 'musical keyboard',
            '🥁' => 'drum',
            '🪘' => 'long drum',
            '🎷' => 'saxophone',
            '🎺' => 'trumpet',
            '🎸' => 'guitar',
            '🪕' => 'banjo',
            '🎻' => 'violin',
            '🎲' => 'game die',
            '♟️' => 'chess pawn',
            '🎯' => 'direct hit',
            '🎳' => 'bowling',
            '🎮' => 'video game',
            '🎰' => 'slot machine',
            '🧩' => 'puzzle piece',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function objects(): array
    {
        return array_merge(
            self::objectsElectronics(),
            self::objectsMedia(),
            self::objectsOffice()
        );
    }

    /**
     * @return array<string, string>
     */
    private static function objectsElectronics(): array
    {
        return [
            '⌚' => 'watch',
            '📱' => 'mobile phone',
            '📲' => 'mobile phone with arrow',
            '💻' => 'laptop',
            '⌨️' => 'keyboard',
            '🖥️' => 'desktop computer',
            '🖨️' => 'printer',
            '🖱️' => 'computer mouse',
            '🖲️' => 'trackball',
            '💽' => 'computer disk',
            '💾' => 'floppy disk',
            '💿' => 'optical disk',
            '📀' => 'dvd',
            '📼' => 'videocassette',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function objectsMedia(): array
    {
        return [
            '📷' => 'camera',
            '📸' => 'camera with flash',
            '📹' => 'video camera',
            '🎥' => 'movie camera',
            '📽️' => 'film projector',
            '📞' => 'telephone receiver',
            '☎️' => 'telephone',
            '📟' => 'pager',
            '📠' => 'fax machine',
            '📺' => 'television',
            '📻' => 'radio',
            '🎙️' => 'studio microphone',
            '⏰' => 'alarm clock',
            '⏱️' => 'stopwatch',
            '⏲️' => 'timer clock',
            '🕰️' => 'mantelpiece clock',
            '💡' => 'light bulb',
            '🔦' => 'flashlight',
            '🕯️' => 'candle',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function objectsOffice(): array
    {
        return [
            '📚' => 'books',
            '📖' => 'open book',
            '📰' => 'newspaper',
            '🗞️' => 'rolled-up newspaper',
            '📑' => 'bookmark tabs',
            '🔖' => 'bookmark',
            '🏷️' => 'label',
            '💰' => 'money bag',
            '🪙' => 'coin',
            '💴' => 'yen banknote',
            '💵' => 'dollar banknote',
            '💶' => 'euro banknote',
            '💷' => 'pound banknote',
            '💳' => 'credit card',
            '🧾' => 'receipt',
            '✉️' => 'envelope',
            '📧' => 'e-mail',
            '📨' => 'incoming envelope',
            '📩' => 'envelope with arrow',
            '📤' => 'outbox tray',
            '📥' => 'inbox tray',
            '📦' => 'package',
            '📪' => 'closed mailbox',
            '📫' => 'closed mailbox with raised flag',
            '📬' => 'open mailbox with raised flag',
            '📭' => 'open mailbox with lowered flag',
            '🗳️' => 'ballot box',
            '✏️' => 'pencil',
            '✒️' => 'black nib',
            '🖊️' => 'pen',
            '🖋️' => 'fountain pen',
            '📝' => 'memo',
            '🔐' => 'locked with key',
            '🔑' => 'key',
            '🗝️' => 'old key',
            '🔒' => 'locked',
            '🔓' => 'unlocked',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function symbols(): array
    {
        return [
            '❤️' => 'red heart',
            '🧡' => 'orange heart',
            '💛' => 'yellow heart',
            '💚' => 'green heart',
            '💙' => 'blue heart',
            '💜' => 'purple heart',
            '🖤' => 'black heart',
            '🤍' => 'white heart',
            '🤎' => 'brown heart',
            '💔' => 'broken heart',
            '❣️' => 'heart exclamation',
            '💕' => 'two hearts',
            '💞' => 'revolving hearts',
            '💓' => 'beating heart',
            '💗' => 'growing heart',
            '💖' => 'sparkling heart',
            '💘' => 'heart with arrow',
            '💝' => 'heart with ribbon',
            '💟' => 'heart decoration',
            '☮️' => 'peace symbol',
            '✝️' => 'latin cross',
            '☪️' => 'star and crescent',
            '🕉️' => 'om',
            '☸️' => 'wheel of dharma',
            '✡️' => 'star of david',
            '🔯' => 'dotted six-pointed star',
            '🕎' => 'menorah',
            '☯️' => 'yin yang',
            '☦️' => 'orthodox cross',
            '🛐' => 'place of worship',
            '⛎' => 'ophiuchus',
            '♈' => 'aries',
            '♉' => 'taurus',
            '♊' => 'gemini',
            '♋' => 'cancer',
            '♌' => 'leo',
            '♍' => 'virgo',
            '♎' => 'libra',
            '♏' => 'scorpio',
            '♐' => 'sagittarius',
            '♑' => 'capricorn',
            '♒' => 'aquarius',
            '♓' => 'pisces',
            '🆔' => 'id button',
            '⚛️' => 'atom symbol',
            '🉑' => 'Japanese "acceptable" button',
            '☢️' => 'radioactive',
            '☣️' => 'biohazard',
            '📴' => 'mobile phone off',
            '📳' => 'vibration mode',
            '✅' => 'check mark button',
            '☑️' => 'check box with check',
            '✔️' => 'check mark',
            '❌' => 'cross mark',
            '❎' => 'cross mark button',
            '➕' => 'plus',
            '➖' => 'minus',
            '➗' => 'divide',
            '✖️' => 'multiply',
            '♾️' => 'infinity',
            '❓' => 'question mark',
            '❔' => 'white question mark',
            '❕' => 'white exclamation mark',
            '❗' => 'exclamation mark',
            '⁉️' => 'exclamation question mark',
            '💯' => 'hundred points',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function flags(): array
    {
        return [
            '🏁' => 'chequered flag',
            '🚩' => 'triangular flag',
            '🎌' => 'crossed flags',
            '🏴' => 'black flag',
            '🏳️' => 'white flag',
            '🏳️‍🌈' => 'rainbow flag',
            '🏳️‍⚧️' => 'transgender flag',
            '🏴‍☠️' => 'pirate flag',
            '🇺🇳' => 'United Nations flag',
            '🇦🇫' => 'Afghanistan flag',
            '🇦🇱' => 'Albania flag',
            '🇩🇿' => 'Algeria flag',
            '🇦🇷' => 'Argentina flag',
            '🇦🇺' => 'Australia flag',
            '🇦🇹' => 'Austria flag',
            '🇧🇪' => 'Belgium flag',
            '🇧🇷' => 'Brazil flag',
            '🇨🇦' => 'Canada flag',
            '🇨🇳' => 'China flag',
            '🇩🇰' => 'Denmark flag',
            '🇪🇬' => 'Egypt flag',
            '🇫🇮' => 'Finland flag',
            '🇫🇷' => 'France flag',
            '🇩🇪' => 'Germany flag',
            '🇬🇷' => 'Greece flag',
            '🇮🇳' => 'India flag',
            '🇮🇩' => 'Indonesia flag',
            '🇮🇪' => 'Ireland flag',
            '🇮🇱' => 'Israel flag',
            '🇮🇹' => 'Italy flag',
            '🇯🇵' => 'Japan flag',
            '🇰🇷' => 'South Korea flag',
            '🇲🇽' => 'Mexico flag',
            '🇳🇱' => 'Netherlands flag',
            '🇳🇿' => 'New Zealand flag',
            '🇳🇴' => 'Norway flag',
            '🇵🇭' => 'Philippines flag',
            '🇵🇱' => 'Poland flag',
            '🇵🇹' => 'Portugal flag',
            '🇷🇺' => 'Russia flag',
            '🇸🇦' => 'Saudi Arabia flag',
            '🇸🇬' => 'Singapore flag',
            '🇿🇦' => 'South Africa flag',
            '🇪🇸' => 'Spain flag',
            '🇸🇪' => 'Sweden flag',
            '🇨🇭' => 'Switzerland flag',
            '🇹🇭' => 'Thailand flag',
            '🇹🇷' => 'Turkey flag',
            '🇦🇪' => 'United Arab Emirates flag',
            '🇬🇧' => 'United Kingdom flag',
            '🇺🇸' => 'United States flag',
            '🇻🇳' => 'Vietnam flag',
        ];
    }

    /**
     * Get all emojis organized by category.
     *
     * @param  array<int, string>  $categories
     * @return array<string, array<string, string>>
     */
    public static function all(array $categories = []): array
    {
        $allEmojis = [
            'smileys' => self::smileys(),
            'people' => self::people(),
            'animals' => self::animals(),
            'food' => self::food(),
            'travel' => self::travel(),
            'activities' => self::activities(),
            'objects' => self::objects(),
            'symbols' => self::symbols(),
            'flags' => self::flags(),
        ];

        if (empty($categories)) {
            return $allEmojis;
        }

        $result = [];
        foreach ($categories as $category) {
            if (isset($allEmojis[$category])) {
                $result[$category] = $allEmojis[$category];
            }
        }

        return $result;
    }
}
