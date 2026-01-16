<?php

declare(strict_types=1);

namespace Accelade\Forms\Components;

use Accelade\Forms\Field;
use Closure;

/**
 * Emoji Input Component
 *
 * A picker for selecting emojis with category support.
 */
class EmojiInput extends Field
{
    protected bool|Closure $searchable = true;

    protected int|Closure $gridColumns = 8;

    protected bool|Closure $showPreview = true;

    protected bool|Closure $multiple = false;

    /** @var array<int, string> */
    protected array $categories = ['smileys', 'people', 'animals', 'food', 'travel', 'activities', 'objects', 'symbols', 'flags'];

    /** @var array<string, array<string, string>>|Closure */
    protected array|Closure $customEmojis = [];

    /**
     * Set the emoji categories to display.
     *
     * @param  array<int, string>  $categories
     */
    public function categories(array $categories): static
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the emoji categories.
     *
     * @return array<int, string>
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Set custom emojis.
     *
     * @param  array<string, array<string, string>>|Closure  $emojis
     */
    public function customEmojis(array|Closure $emojis): static
    {
        $this->customEmojis = $emojis;

        return $this;
    }

    /**
     * Get custom emojis.
     *
     * @return array<string, array<string, string>>
     */
    public function getCustomEmojis(): array
    {
        return $this->evaluate($this->customEmojis);
    }

    /**
     * Enable/disable search functionality.
     */
    public function searchable(bool|Closure $condition = true): static
    {
        $this->searchable = $condition;

        return $this;
    }

    /**
     * Get searchable state.
     */
    public function isSearchable(): bool
    {
        return $this->evaluate($this->searchable);
    }

    /**
     * Set the number of grid columns.
     */
    public function gridColumns(int|Closure $columns): static
    {
        $this->gridColumns = $columns;

        return $this;
    }

    /**
     * Get grid columns.
     */
    public function getGridColumns(): int
    {
        return $this->evaluate($this->gridColumns);
    }

    /**
     * Show/hide emoji preview.
     */
    public function showPreview(bool|Closure $condition = true): static
    {
        $this->showPreview = $condition;

        return $this;
    }

    /**
     * Get show preview state.
     */
    public function getShowPreview(): bool
    {
        return $this->evaluate($this->showPreview);
    }

    /**
     * Enable multiple emoji selection.
     */
    public function multiple(bool|Closure $condition = true): static
    {
        $this->multiple = $condition;

        return $this;
    }

    /**
     * Check if multiple selection is enabled.
     */
    public function isMultiple(): bool
    {
        return $this->evaluate($this->multiple);
    }

    /**
     * Get all emojis organized by category.
     *
     * @return array<string, array<string, string>>
     */
    public function getEmojis(): array
    {
        $customEmojis = $this->getCustomEmojis();

        if (! empty($customEmojis)) {
            return $customEmojis;
        }

        return $this->getDefaultEmojis();
    }

    /**
     * Get default emojis by category.
     *
     * @return array<string, array<string, string>>
     */
    protected function getDefaultEmojis(): array
    {
        $allEmojis = [
            'smileys' => [
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
            ],
            'people' => [
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
            ],
            'animals' => [
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
            ],
            'food' => [
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
            ],
            'travel' => [
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
            ],
            'activities' => [
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
            ],
            'objects' => [
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
            ],
            'symbols' => [
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
            ],
            'flags' => [
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
            ],
        ];

        $result = [];
        foreach ($this->categories as $category) {
            if (isset($allEmojis[$category])) {
                $result[$category] = $allEmojis[$category];
            }
        }

        return $result;
    }

    /**
     * Get the category labels.
     *
     * @return array<string, string>
     */
    public function getCategoryLabels(): array
    {
        return [
            'smileys' => 'Smileys & Emotion',
            'people' => 'People & Body',
            'animals' => 'Animals & Nature',
            'food' => 'Food & Drink',
            'travel' => 'Travel & Places',
            'activities' => 'Activities',
            'objects' => 'Objects',
            'symbols' => 'Symbols',
            'flags' => 'Flags',
        ];
    }

    /**
     * Get the category icons (using emojis as icons).
     *
     * @return array<string, string>
     */
    public function getCategoryIcons(): array
    {
        return [
            'smileys' => '😀',
            'people' => '👋',
            'animals' => '🐶',
            'food' => '🍎',
            'travel' => '🚗',
            'activities' => '⚽',
            'objects' => '💡',
            'symbols' => '❤️',
            'flags' => '🏁',
        ];
    }

    /**
     * Get the view name.
     */
    protected function getView(): string
    {
        return 'forms::components.emoji-input';
    }
}
