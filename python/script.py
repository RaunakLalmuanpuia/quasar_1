# def say_hello():
#     return "Hello, sunny!"

# print(say_hello())

from transformers import pipeline

def predict_sentiment(text):
    # Load pre-trained sentiment analysis model
    sentiment_classifier = pipeline("sentiment-analysis")

    # Predict sentiment of the given text
    result = sentiment_classifier(text)

    return result[0]

# Example usage
text = "I love this movie, it's fantastic!"
result = predict_sentiment(text)
print(result)
