<?php

declare(strict_types=1);

namespace Accelade\Forms\Data;

/**
 * Predefined emoji icons for IconPicker component.
 */
final class IconEmojiData
{
    /**
     * @return array<string, string>
     */
    public static function smileys(): array
    {
        return [
            '😀' => 'grinning',
            '😃' => 'smiley',
            '😄' => 'smile',
            '😁' => 'grin',
            '😆' => 'laughing',
            '😅' => 'sweat-smile',
            '🤣' => 'rofl',
            '😂' => 'joy',
            '🙂' => 'slightly-smiling',
            '🙃' => 'upside-down',
            '😉' => 'wink',
            '😊' => 'blush',
            '😇' => 'innocent',
            '🥰' => 'smiling-hearts',
            '😍' => 'heart-eyes',
            '🤩' => 'star-struck',
            '😘' => 'kissing-heart',
            '😗' => 'kissing',
            '😚' => 'kissing-closed-eyes',
            '😙' => 'kissing-smiling-eyes',
            '🥲' => 'smiling-tear',
            '😋' => 'yum',
            '😛' => 'stuck-out-tongue',
            '😜' => 'stuck-out-tongue-wink',
            '🤪' => 'zany',
            '😝' => 'stuck-out-tongue-closed-eyes',
            '🤑' => 'money-mouth',
            '🤗' => 'hugging',
            '🤭' => 'hand-over-mouth',
            '🤫' => 'shushing',
            '🤔' => 'thinking',
            '🤐' => 'zipper-mouth',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function people(): array
    {
        return [
            '👋' => 'wave',
            '🤚' => 'raised-back',
            '🖐️' => 'hand-splayed',
            '✋' => 'raised-hand',
            '🖖' => 'vulcan',
            '👌' => 'ok-hand',
            '🤌' => 'pinched-fingers',
            '🤏' => 'pinching-hand',
            '✌️' => 'victory',
            '🤞' => 'crossed-fingers',
            '🤟' => 'love-you',
            '🤘' => 'horns',
            '🤙' => 'call-me',
            '👈' => 'point-left',
            '👉' => 'point-right',
            '👆' => 'point-up',
            '🖕' => 'middle-finger',
            '👇' => 'point-down',
            '☝️' => 'point-up-2',
            '👍' => 'thumbs-up',
            '👎' => 'thumbs-down',
            '✊' => 'fist',
            '👊' => 'punch',
            '🤛' => 'fist-left',
            '🤜' => 'fist-right',
            '👏' => 'clap',
            '🙌' => 'raised-hands',
            '👐' => 'open-hands',
            '🤲' => 'palms-up',
            '🤝' => 'handshake',
            '🙏' => 'pray',
            '💪' => 'muscle',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function animals(): array
    {
        return [
            '🐶' => 'dog',
            '🐱' => 'cat',
            '🐭' => 'mouse',
            '🐹' => 'hamster',
            '🐰' => 'rabbit',
            '🦊' => 'fox',
            '🐻' => 'bear',
            '🐼' => 'panda',
            '🐨' => 'koala',
            '🐯' => 'tiger',
            '🦁' => 'lion',
            '🐮' => 'cow',
            '🐷' => 'pig',
            '🐸' => 'frog',
            '🐵' => 'monkey',
            '🐔' => 'chicken',
            '🐧' => 'penguin',
            '🐦' => 'bird',
            '🐤' => 'chick',
            '🦆' => 'duck',
            '🦅' => 'eagle',
            '🦉' => 'owl',
            '🦇' => 'bat',
            '🐺' => 'wolf',
            '🐗' => 'boar',
            '🐴' => 'horse',
            '🦄' => 'unicorn',
            '🐝' => 'bee',
            '🐛' => 'bug',
            '🦋' => 'butterfly',
            '🐌' => 'snail',
            '🐙' => 'octopus',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function food(): array
    {
        return [
            '🍎' => 'apple',
            '🍐' => 'pear',
            '🍊' => 'orange',
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
            '🥝' => 'kiwi',
            '🍅' => 'tomato',
            '🥑' => 'avocado',
            '🍆' => 'eggplant',
            '🥔' => 'potato',
            '🥕' => 'carrot',
            '🌽' => 'corn',
            '🌶️' => 'pepper',
            '🥒' => 'cucumber',
            '🥬' => 'leafy-green',
            '🥦' => 'broccoli',
            '🧄' => 'garlic',
            '🧅' => 'onion',
            '🍄' => 'mushroom',
            '🥜' => 'peanuts',
            '🌰' => 'chestnut',
            '🍞' => 'bread',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function objects(): array
    {
        return [
            '⌚' => 'watch',
            '📱' => 'phone',
            '💻' => 'laptop',
            '⌨️' => 'keyboard',
            '🖥️' => 'desktop',
            '🖨️' => 'printer',
            '🖱️' => 'mouse',
            '💽' => 'disc',
            '💾' => 'floppy',
            '💿' => 'cd',
            '📀' => 'dvd',
            '🎥' => 'movie-camera',
            '🎬' => 'clapper',
            '📺' => 'tv',
            '📷' => 'camera',
            '📸' => 'camera-flash',
            '📹' => 'video-camera',
            '📼' => 'vhs',
            '🔍' => 'magnifying-left',
            '🔎' => 'magnifying-right',
            '🕯️' => 'candle',
            '💡' => 'bulb',
            '🔦' => 'flashlight',
            '📔' => 'notebook',
            '📕' => 'closed-book',
            '📖' => 'open-book',
            '📗' => 'green-book',
            '📘' => 'blue-book',
            '📙' => 'orange-book',
            '📚' => 'books',
            '📓' => 'notebook-2',
            '📒' => 'ledger',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function symbols(): array
    {
        return [
            '❤️' => 'red-heart',
            '🧡' => 'orange-heart',
            '💛' => 'yellow-heart',
            '💚' => 'green-heart',
            '💙' => 'blue-heart',
            '💜' => 'purple-heart',
            '🖤' => 'black-heart',
            '🤍' => 'white-heart',
            '🤎' => 'brown-heart',
            '💔' => 'broken-heart',
            '❣️' => 'heart-exclamation',
            '💕' => 'two-hearts',
            '💞' => 'revolving-hearts',
            '💓' => 'heartbeat',
            '💗' => 'growing-heart',
            '💖' => 'sparkling-heart',
            '💘' => 'cupid',
            '💝' => 'gift-heart',
            '⭐' => 'star',
            '🌟' => 'glowing-star',
            '✨' => 'sparkles',
            '⚡' => 'zap',
            '🔥' => 'fire',
            '💥' => 'boom',
            '☀️' => 'sun',
            '🌙' => 'moon',
            '🌈' => 'rainbow',
            '☁️' => 'cloud',
            '❄️' => 'snowflake',
            '💧' => 'droplet',
            '🎯' => 'target',
            '✅' => 'check-mark',
        ];
    }

    /**
     * Get all emoji icons organized by category.
     *
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        return [
            'smileys' => self::smileys(),
            'people' => self::people(),
            'animals' => self::animals(),
            'food' => self::food(),
            'objects' => self::objects(),
            'symbols' => self::symbols(),
        ];
    }
}
